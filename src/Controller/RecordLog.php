<?php

namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class RecordLog extends Controller {

    /**
     * @var RecordRepository
     */
    private $recordRepository;

    public function __construct(RecordRepository $recordRepository) {
        $this->recordRepository = $recordRepository;
    }

    /**
     * @Route("/log/record", name="recordLog")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
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
     * @Route("/log/record/download", name="recordLogDownload")
     * @return Response
     */
    public function download() {
        $count = $this->recordRepository->getCountOfAllRecords();
        $result = $this->recordRepository->getRecord(0, $count);

        $content = "";
        foreach ($result as $item) {
            $stringID = $item->getStringID();
            $date = $item->getTime()->format('Y-m-d H:i:s');
            $content .= "$stringID $date\n";
        }

        $response = new Response($content);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'records.txt'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}