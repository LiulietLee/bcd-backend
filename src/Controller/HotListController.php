<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\SearchResult;
use App\Type\CoverType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotListController extends AbstractController {

    /**
     * @Route("/hot_list", name="hotList")
     * @return Response
     */
    public function index() {
        $result = $this->coverRecordRepository()->findByTypeAfterTime(CoverType::Null, time() - 7 * 24 * 60 * 60);

        $list = Array();
        foreach ($result as $item) {
            $listItem = new \stdClass();

            $listItem->id = $item->getStringID();
            $listItem->author = $item->getAuthor();
            $listItem->title = $item->getTitle();
            $listItem->url = $item->getURL();
            $listItem->count = $item->getDownloadCount();

            $list[] = $listItem;
        }

        return $this->render('hotList.html.twig', array(
            'list' => $list,
        ));
    }

}