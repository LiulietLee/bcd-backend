<?php

namespace App\Model;

use App\Entity\SearchResult;
use App\Type\CoverType;
use App\Model\SpiderURLGenerator;

class CoverHacker {

    public function getCoverByTypeAndNID(int $type, int $nid, string $id = "") {
        $spiderURL = null;
        switch ($type) {
            case CoverType::Video:
                $spiderURL = SpiderURLGenerator::avCoverInfoURLByAID($nid);
                break;
            default:
                // TODO other spiders are under building...
                break;
        }

        $json = file_get_contents($spiderURL);
        $jsonData = json_decode($json);
        $jsonData->id = $id;

        if (property_exists($jsonData, "error")) {
            return $jsonData->reason;
        }

        $result = new SearchResult($jsonData);

        return $result;
    }

}