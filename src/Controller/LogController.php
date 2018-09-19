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
     * @Route("/log/record", name="recordLog")
     * @param Request $request
     * @return Response
     */
    public function recordLog(Request $request) {
        $page = $request->query->getInt("page", 0);
        if ($page < 0) {
            $page = 0;
        }
        $limit = 20;
        $offset = $page * $limit;
        $count = $this->recordRepository->getCountOfAllRecords();
        while ($offset >= $count) {
            $offset -= $limit;
            $page--;
            if ($offset < 0) {
                $offset = $page = 0;
                break;
            }
        }

        $result = $this->recordRepository->getRecord($offset, $limit);

        return $this->render('recordLog.html.twig', array(
            'count' => $count,
            'page' => $page,
            'list' => $result,
        ));
    }

    /**
     * @Route("/log/cover", name="coverLog")
     * @param Request $request
     * @return Response
     */
    public function coverLog(Request $request) {
        $page = $request->query->getInt("page", 0);
        if ($page < 0) {
            $page = 0;
        }
        $limit = 20;
        $offset = $page * $limit;
        $count = $this->coverRepository->getCountOfAllCovers();
        while ($offset >= $count) {
            $offset -= $limit;
            $page--;
            if ($offset < 0) {
                $page = $offset = 0;
                break;
            }
        }

        $title = $request->query->get("title");
        $author = $request->query->get("author");
        $stringID = $request->query->get("stringID");

        $checkList = ["av", "cv", "lv"];
        foreach ($checkList as $item) {
            if ($stringID == $item) {
                $stringID = null;
                break;
            }
        }

        $result = $this->coverRepository->findCoverByTitleAndAuthorAndStringID($title, $author, $stringID, $offset, $limit);

        return $this->render('coverLog.html.twig', array(
            'count' => $count,
            'title' => $title,
            'author' => $author,
            'stringID' => $stringID,
            'page' => $page,
            'list' => $result,
        ));
    }

}
