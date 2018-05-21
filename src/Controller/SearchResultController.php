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

        $record = $this->coverRecordRepository()->findOnyByTypeAndNID($type, $nid);
        if ($record) {
            $this->update($record);

            $result = new \stdClass();
            $result->title = $record->getTitle();
            $result->author = $record->getAuthor();
            $result->url = $record->getURL();

            return $this->render('result.html.twig', array(
                'title' => $result->title,
                'author' => $result->author,
                'coverURL' => $result->url,
            ));
        }

        $result = $this->getCover($type, $nid);
        if (!property_exists($result, "error")) {
            if (!$record) {
                $record = $this->coverRecordRepository()->create($type, $result->getURL(), $nid, $result->getTitle(), $result->getAuthor());
                $this->insert($record);
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

}
