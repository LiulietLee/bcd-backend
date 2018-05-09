<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\SearchResult;
use App\Type\CoverType;
use Symfony\Component\Routing\Annotation\Route;

class HotListController extends AbstractController {

    /**
     * @Route("/hot_list", name="hotList")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index() {
        $result = $this->repository()->findByTypeAfterTime(CoverType::Null, time() - 7 * 24 * 60 * 60);
        $list = [];
        foreach ($result as $item) {
            $result = new \stdClass();
            $result->id = CoverType::typeToStringWithTypeAndNID($item->getType(), $item->getNid());
            $result->url = $item->getUrl();
            print_r($result);
            $list[] = json_encode($result);
        }

        return $this->json($list);
    }

}