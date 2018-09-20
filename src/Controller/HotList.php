<?php

namespace App\Controller;

use App\Manager\HotListManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Routing\Annotation\Route;

class HotList extends Controller {

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
        $response = $this->render('hotList.html.twig', array(
            'list' => $this->getListFromCache(),
        ));

        return $response;
    }

    /**
     * @Route("/api/hot_list", name="hotListAPI")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function api() {
        $result = $this->getListFromCache();

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

    /**
     * @return \App\Entity\Cover[]
     */
    private function getListFromCache(): Array {
        $cache = new FilesystemAdapter();
        try {
            $result = $cache->getItem('hotList');
        } catch (InvalidArgumentException $e) {
            return [];
        }
        if ($result->isHit()) {
            return $result->get();
        } else {
            $list = $this->hotListManager->getHotList();
            $result->set($list);
            $result->expiresAfter(600);
            $cache->save($result);
            return $list;
        }
    }
}