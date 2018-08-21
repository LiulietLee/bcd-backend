<?php

use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $limit = $request->query->getInt("limit", 0);
        $offset = $request->query->getInt("offset", 0);
        if ($limit < 0 || $offset < 0) {
            throw $this->createNotFoundException("出错了呢...");
        }

        $title = $request->request->get("title");
        $author = $request->request->get("author");
        $stringID = $request->request->get("stringID");

        $result = $this->coverRepository->findCoverByTitleAndAuthorAndStringID($title, $author, $stringID, $offset, $limit);
        $list = [];
        foreach ($result as $item) {
            $newItem = $item->stdClass();
            $list[] = $newItem;
        }

        return $this->render('coverLog.html.twig', array(
            'list' => $list
        ));
    }

}
