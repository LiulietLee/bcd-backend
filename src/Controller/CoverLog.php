<?php

namespace App\Controller;

use App\Repository\CoverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class CoverLog extends Controller {

    /**
     * @var CoverRepository
     */
    private $coverRepository;

    public function __construct(CoverRepository $coverRepository) {
        $this->coverRepository = $coverRepository;
    }

    /**
     * @Route("/log/cover", name="coverLog")
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

    /**
     * @Route("/log/cover/download", name="coverLogDownload")
     * @return Response
     */
    public function download() {
        $count = $this->coverRepository->getCountOfAllCovers();
        $result = $this->coverRepository->findCoverByTitleAndAuthorAndStringID(null, null, null, 0, $count);

        $content = "";
        foreach ($result as $item) {
            $stringID = $item->getStringID();
            $title = $item->getTitle();
            $author = $item->getAuthor();
            $url = $item->getURL();
            $content .= "$stringID\n$title\n$author\n$url\n\n";
        }

        $response = new Response($content);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            "covers.txt"
        );
        $response->headers->set("Content-Disposition", $disposition);
        return $response;
    }
}
