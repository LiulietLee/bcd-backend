<?php

namespace App\Controller\API;

use App\Controller\AbstractController;
use App\Type\CoverType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController {

    /**
     * @Route("/api/search", name="searchAPI")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        $typeString = $request->query->get("type");
        $nid = $request->query->get("nid");
        $type = CoverType::typeFromString($typeString);
        $stringID = CoverType::typeToStringWithTypeAndNID($type, $nid);

        if (!$nid || !$type) {
            $result = [
                'error' => 1,
                'message' => 'api 格式错误',
            ];

            $response = new Response(json_encode($result));
        } else {
            $cover = $this->getCoverFromDB($stringID);

            if ($cover) {
                $this->insertRecord($stringID);

                $result = new \stdClass();
                $result->title = $cover->getTitle();
                $result->author = $cover->getAuthor();
                $result->url = $cover->getURL();

                $response = new Response(json_encode($result));
            } else {
                $hackResult = $this->getCoverFromCoverHacker($type, $nid);
                if (!property_exists($hackResult, "error")) {
                    $cover = $this->coverRepository()->create(
                        $stringID,
                        $hackResult->getURL(),
                        $hackResult->getTitle(),
                        $hackResult->getAuthor()
                    );
                    $this->updateOrCreateCover($cover);

                    $response = new Response(json_encode($hackResult->stdClass()));
                } else {
                    $result = [
                        'error' => 2,
                        'message' => '查找封面时出问题啦',
                    ];
                    $response = new Response(json_encode($result));
                }
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
