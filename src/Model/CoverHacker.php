<?php

namespace App\Model;

use App\Entity\CoverRecord;
use App\Entity\SearchResult;
use App\Type\CoverType;
use App\Model\SpiderURLGenerator;

class CoverHacker {

    /**
     * @param int $type
     * @param int $nid
     * @return SearchResult|null
     */
    public function getCoverByTypeAndNID(int $type, int $nid) {
        $spiderURL = null;
        switch ($type) {
            case CoverType::Video:
                $spiderURL = SpiderURLGenerator::avCoverInfoURLByAID($nid);
                break;
            case CoverType::Article:
                $spiderURL = SpiderURLGenerator::cvCoverInfoURLByCID($nid);
                break;
            case CoverType::Live:
                $spiderURL = SpiderURLGenerator::lvCoverInfoURLByLID($nid);
                break;
            default:
                return null;
        }

        $json = file_get_contents($spiderURL);
        $jsonData = json_decode($json);
        $jsonData->stringID = CoverType::getStringIDByTypeAndNID($type, $nid);

        if (property_exists($jsonData, "error")) {
            return $jsonData;
        }

        $result = new SearchResult($jsonData);

        return $result;
    }

}