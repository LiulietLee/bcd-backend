<?php

namespace App\Controller\API;

use App\Controller\AbstractController;
use App\Entity\CoverRecord;
use App\Type\CoverType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DBController extends AbstractController {

    /**
     * @Route("api/db/update", name="DBUpdate")
     *
     * @param Request $request
     * @return Response
     */
    function updateRecord(Request $request) {
        $typeString = $request->query->get("type");
        $type = CoverType::typeFromString($typeString);
        $nid = $request->query->get("nid");
        $url = $request->query->get("url");
        $title = $request->query->get("title");
        $author = $request->query->get("author");

        $record = $this->coverRecordRepository()->findOneByTypeAndNID($type, $nid);
        if ($request) {
            $timeInterface = new \DateTime();
            $count = $record->getDownloadCount();
            $record->setDownloadCount($count + 1);
            $record->setTime($timeInterface);
            $record->setTitle($title);
            $record->setAuthor($author);
            $record->setURL($url);
            $this->entityManager()->flush();
        } else {
            $record = $this->coverRecordRepository()->create($type, $url, $nid, $title, $author);
            $this->insert($record);
        }

        $response = new Response(json_encode(["message" => "OK"]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/db/search", name="DBSearch")
     *
     * @param Request $request
     * @return Response
     */
    function getRecord(Request $request) {
        $typeString = $request->query->get("type");
        $type = CoverType::typeFromString($typeString);
        $nid = $request->query->get("nid");
        $record = $this->coverRecordRepository()->findOneByTypeAndNID($type, $nid);

        if ($record) {
            $result = new \stdClass();
            $result->title = $record->getTitle();
            $result->url = $record->getURL();
            $result->author = $record->getAuthor();
            $response = new Response(json_encode($result));
        } else {
            $result = [
                "error" => 2,
                "message" => "云娘找不到相关数据呢",
            ];
            $response = new Response(json_encode($result));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}