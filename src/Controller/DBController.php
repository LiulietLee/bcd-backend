<?php

namespace App\Controller;

use App\Manager\CoverManager;
use App\Type\CoverType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DBController extends BaseController {

    /**
     * @var CoverManager
     */
    private $coverManager;

    public function __construct(CoverManager $coverManager) {
        $this->coverManager = $coverManager;
    }

    /**
     * @Route("api/db/update", name="DBUpdate")
     *
     * @param Request $request
     * @return Response
     */
    function updateRecord(Request $request) {
        $content = $request->getContent();
        $result = ["status" => 500, "message" => "empty content"];

        if (!empty($content)) {
            $params = json_decode($content, true);

            if ($this->needRedirect()) {
                $res = $this->redirectWithPath('/api/db/update', 'POST', $params);
                if ($res) { return $res; }
            }

            $typeString = $params["type"];
            $type = CoverType::typeFromString($typeString);
            $nid = $params["nid"];
            $url = $params["url"];
            $title = $params["title"];
            $author = $params["author"];
            $stringID = CoverType::getStringIDByTypeAndNID($type, $nid);

            if ($stringID != "av1") {
                $this->coverManager->updateOrCreateCoverBy($stringID, $title, $url, $author);
                $result = ["status" => 200, "message" => "OK"];
            }
        }

        $response = new Response(json_encode($result));
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
        $stringID = CoverType::getStringIDByTypeAndNID($type, $nid);
        $record = $this->coverManager->getCoverFromDB($stringID);

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