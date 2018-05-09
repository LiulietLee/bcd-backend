<?php

namespace App\Controller;

use App\Entity\CoverRecord;
use App\Controller\AbstractController;
use App\Type\CoverType;
use App\Model\CoverHacker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SearchResultController extends AbstractController {

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
        $hacker = new CoverHacker();


        $record = $this->repository()->findOnyByTypeAndNID($type, $nid);
        if ($record) {
            $count = $record->getDownloadCount();
            $record->setDownloadCount($count + 1);
            $this->entityManager()->flush();
        }

        $result = $hacker->getCoverByTypeAndNID($type, $nid, $content);
        if ($result) {
            if (!$record) {
                $record = $this->repository()->create($type, $result->getURL(), $nid);
                $this->insert($record);
            }

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
