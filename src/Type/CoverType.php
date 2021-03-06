<?php

namespace App\Type;

class CoverType {

    const Null = 0;
    const Video = 1;
    const Article = 2;
    const Live = 3;
    const BVideo = 4;

    static function typeFromString(string $str): ?int {
        $lowerStr = strtolower($str);
        switch ($lowerStr) {
            case "av": return CoverType::Video;
            case "cv": return CoverType::Article;
            case "lv": return CoverType::Live;
            case "bv": return CoverType::BVideo;
            default: return null;
        }
    }

    static function getStringIDByTypeAndNID(int $type, string $nid): ?string {
        switch ($type) {
            case CoverType::Video:
                $id = "av";
                break;
            case CoverType::Live:
                $id = "lv";
                break;
            case CoverType::Article:
                $id = "cv";
                break;
            case CoverType::BVideo:
                $id = "BV";
                break;
            default: return null;
        }

        return $id. $nid;
    }

}