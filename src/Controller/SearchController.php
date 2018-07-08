<?php

namespace App\Controller;

use App\Manager\CoverManager;
use App\Repository\CoverRepository;
use App\Type\CoverType;
use App\Model\CoverHacker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends Controller {

    /**
     * @var CoverManager
     */
    private $coverManager;

    /**
     * @var CoverRepository
     */
    private $coverRepository;

    public function __construct(CoverManager $coverManager, CoverRepository $coverRepository) {
        $this->coverManager = $coverManager;
        $this->coverRepository = $coverRepository;
    }

    /**
     * @Route("/{content}", name="searchContent")
     * @param string $content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(string $content) {
        if (strlen($content) < 3) {
            throw $this->createAccessDeniedException('搜索内容太短啦！');
        }

        $typeString = substr($content, 0, 2);
        $type = CoverType::typeFromString($typeString);
        if (!$type) {
            throw $this->createNotFoundException('请检查一下格式哦');
        }

        $nid = substr($content, 2, strlen($content) - 2);

        $cover = $this->coverManager->getCoverFromDB($content);
        if ($cover) {
            $this->coverManager->updateOrCreateCover($cover);
            return $this->render('result.html.twig', array(
                'title' => $cover->getTitle(),
                'author' => $cover->getAuthor(),
                'coverURL' => $cover->getURL(),
            ));
        }

        $result = $this->getCoverFromCoverHacker($type, $nid);
        if (!property_exists($result, "error")) {
            if (!$cover) {
                $cover = $this->coverRepository->create(
                    $content,
                    $result->getURL(),
                    $result->getTitle(),
                    $result->getAuthor()
                );
                $this->coverManager->updateOrCreateCover($cover);
            }

            return $this->render('result.html.twig', array(
                'title' => $result->getTitle(),
                'author' => $result->getAuthor(),
                'coverURL' => $result->getURL(),
            ));
        } else {
            // TODO error code
            throw $this->createNotFoundException('找不到封面呢……');
        }
    }

    /**
     * @Route("/api/search", name="searchAPI")
     * @param Request $request
     * @return Response
     */
    public function api(Request $request) {
        $typeString = $request->query->get("type");
        $nid = $request->query->get("nid");
        $type = CoverType::typeFromString($typeString);
        $stringID = CoverType::getStringIDByTypeAndNID($type, $nid);

        if (!$nid || !$type) {
            $result = [
                'error' => 1,
                'message' => 'api 格式错误',
            ];

            $response = new Response(json_encode($result));
        } else {
            $cover = $this->coverManager->getCoverFromDB($stringID);

            if ($cover) {
                $this->coverManager->insertRecord($cover);
                $result = $cover->stdClass();
                $response = new Response(json_encode($result));
            } else {
                $hackResult = $this->getCoverFromCoverHacker($type, $nid);
                if (!property_exists($hackResult, "error")) {
                    $cover = $this->coverRepository->create(
                        $stringID,
                        $hackResult->getURL(),
                        $hackResult->getTitle(),
                        $hackResult->getAuthor()
                    );
                    $this->coverManager->updateOrCreateCover($cover);

                    $response = new Response(json_encode($hackResult->stdClass()));
                } else {
                    $result = [
                        'error' => 2,
                        'message' => '查找封面时出问题啦',
                    ];
                    $response = new Response(json_encode($result));
                }
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @param int $type
     * @param int $nid
     * @return \App\Entity\SearchResult|null
     */
    private function getCoverFromCoverHacker(int $type, int $nid) {
        $hacker = new CoverHacker();
        $result = $hacker->getCoverByTypeAndNID($type, $nid);
        return $result;
    }
}
