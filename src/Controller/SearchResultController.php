<?php

namespace App\Controller;

use App\Entity\SearchRecord;
use App\Controller\AbstractController;
use App\Type\CoverType;
use App\Model\CoverHacker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchResultController extends AbstractController {

    /**
     * @Route("/search/{content}", name="searchContent")
     * @param string $content
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(string $content, Request $request) {
        if (strlen($content) < 3) {
            throw $this->createAccessDeniedException('搜索内容太短啦！');
        }

        $typeString = substr($content, 0, 2);
        $type = CoverType::typeFromString($typeString);
        if (!$type) {
            throw $this->createNotFoundException('请检查一下格式哦');
        }

        $nid = substr($content, 2, strlen($content) - 2);
        $hacker = new CoverHacker();
        $result = $hacker->getCoverByTypeAndNID($type, $nid);
        if ($result) {
            // TODO need refactor
            $record = new SearchRecord();

            $zone = new \DateTimeZone("	Asia/Shanghai");
            $timeInterface = new \DateTime("now", $zone);
            $record->setTime($timeInterface);
            $record->setType($type);
            $record->setCoverURL($result->getURL());
            $record->setDownloadCount(1);
            $record->setNid($nid);

            $this->insert($record);

            return $this->render('result.html.twig', array(
                'title' => $result->getTitle(),
                'author' => $result->getAuthor(),
                'coverURL' => $result->getURL(),
            ));
        } else {
            throw $this->createNotFoundException('找不到封面呢……');
        }
    }

}
