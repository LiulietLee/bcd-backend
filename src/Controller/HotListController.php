<?php

namespace App\Controller;

use App\Manager\HotListManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HotListController extends Controller {

    /**
     * @var HotListManager
     */
    private $hotListManager;

    public function __construct(HotListManager $hotListManager) {
        $this->hotListManager = $hotListManager;
    }

    /**
     * @Route("/hot_list", name="hotList")
     * @return Response
     */
    public function index() {
        return $this->render('hotList.html.twig', array(
            'list' => $this->getList(),
        ));
    }

    /**
     * @Route("/api/hot_list", name="hotListAPI")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function api() {
        $list = json_encode($this->getList());

        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @return \stdClass[]
     */
    public function getList() {
        $result = $this->hotListManager->getHotList();

        $list = Array();
        foreach ($result as $item) {
            $listItem = $item->stdClass();
            $list[] = $listItem;
        }

        return $list;
    }

}