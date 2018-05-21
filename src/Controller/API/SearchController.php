<?php

namespace App\Controller\API;

use App\Controller\AbstractController;
use App\Model\CoverHacker;
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

        if (!$nid || !$type) {
            $result = [
                'error' => 1,
                'message' => 'api 格式错误',
            ];

            $response = new Response(json_encode($result));
        } else {
            $record = $this->coverRecordRepository()->findOnyByTypeAndNID($type, $nid);

            if ($record) {
                $count = $record->getDownloadCount();
                $record->setDownloadCount($count + 1);
                $this->entityManager()->flush();

                $result = new \stdClass();
                $result->title = $record->getTitle();
                $result->author = $record->getAuthor();
                $result->url = $record->getURL();

                $response = new Response(json_encode($result));
            } else {
                $hackResult = $this->getCover($type, $nid);
                if (!property_exists($hackResult, "error")) {
                    $record = $this->coverRecordRepository()->create($type, $hackResult->getURL(), $nid, $hackResult->getTitle(), $hackResult->getAuthor());
                    $this->insert($record);

                    $response = new Response($hackResult->stdClass());
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
