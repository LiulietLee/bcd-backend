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
        $result = $this->getHotList();

        $list = Array();
        foreach ($result as $item) {
            $listItem = new \stdClass();

            $listItem->id = $item->getStringID();
            $listItem->author = $item->getAuthor();
            $listItem->title = $item->getTitle();
            $listItem->url = $item->getURL();

            $list[] = $listItem;
        }

        return $this->render('hotList.html.twig', array(
            'list' => $list,
        ));
    }

}