<?php

namespace App\Type;

class CoverType {

    const Video = 1;
    const Article = 2;
    const Live = 3;

    static function typeFromString(string $str): ?CoverType {
        $lowerStr = strtolower($str);
        switch ($lowerStr) {
            case "av": return CoverType::Video;
            case "cv": return CoverType::Article;
            case "lv": return CoverType::Live;
            default: return null;
        }
    }

}