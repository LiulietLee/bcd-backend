<?php

namespace App\Controller;

use App\Manager\HotListManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotList extends BaseController {

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
            'list' => $this->hotListManager->getHotList(),
        ));
    }

    /**
     * @Route("/api/hot_list", name="hotListAPI")
     * @return JsonResponse
     */
    public function api() {
        if ($this->needRedirect()) {
            return $this->redirect($this->redirectURL('/api/hot_list'));
        }

        $result = $this->hotListManager->getHotList();

        $list = [];
        foreach ($result as $item) {
            $listItem = $item->stdClass();
            $list[] = $listItem;
        }

        $list = json_encode($list);
        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}