<?php

namespace App\Controller\API;

use App\Controller\AbstractController;
use App\Entity\SearchResult;
use App\Type\CoverType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotListController extends AbstractController {

    /**
     * @Route("/api/hot_list", name="hotListAPI")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index() {
        $result = $this->coverRecordRepository()->findByTypeAfterTime(CoverType::Null, time() - 7 * 24 * 60 * 60);

        $list = Array();
        foreach ($result as $item) {
            $listItem = new \stdClass();

            $listItem->id = $item->getStringID();
            $listItem->author = $item->getAuthor();
            $listItem->title = $item->getTitle();
            $listItem->url = $item->getUrl();

            $list[] = $listItem;
        }

        $list = json_encode($list);

        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}