<?php

namespace App\Model;

class SpiderURLGenerator {

    private static $baseURL = "https://bilibilicd.vapor.cloud/";

    static function avCoverInfoURLByAID(int $aid): string {
        $fullURL = self::$baseURL. "/av/info/$aid";
        return $fullURL;
    }

}