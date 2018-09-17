<?php

namespace App\Controller;

use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends Controller {

    /**
     * @var CoverRepository
     */
    private $coverRepository;

    /**
     * @var RecordRepository
     */
    private $recordRepository;

    public function __construct(CoverRepository $coverRepository, RecordRepository $recordRepository) {
        $this->coverRepository = $coverRepository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * @Route("/log/cover", name="coverLog")
     * @param Request $request
     * @return Response
     */
    public function coverLog(Request $request) {
        $page = $request->query->getInt("page", 0);
        $limit = 20;
        $offset = $page * $limit;
        if ($page < 0) {
            $page = 0;
        }

        $title = $request->query->get("title");
        $author = $request->query->get("author");
        $stringID = $request->query->get("stringID");

        $result = $this->coverRepository->findCoverByTitleAndAuthorAndStringID($title, $author, $stringID, $offset, $limit);
        $list = [];
        foreach ($result as $item) {
            $newItem = $item->stdClass();
            $list[] = $newItem;
        }

        return $this->render('coverLog.html.twig', array(
            'page' => $page,
            'list' => $list
        ));
    }

}
