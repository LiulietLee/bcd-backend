<?php

namespace App\Model;

class SpiderURLGenerator {

    private static $baseURL = "https://bilibilicd.herokuapp.com";

    static function avCoverInfoURLByAID(int $aid): string {
        $fullURL = self::$baseURL. "/av/info/$aid";
        return $fullURL;
    }

    static function cvCoverInfoURLByCID(int $cid): string {
        $fullURL = self::$baseURL. "/cv/info/$cid";
        return $fullURL;
    }

    static function lvCoverInfoURLByLID(int $lid): string {
        $fullURL = self::$baseURL. "/lv/info/$lid";
        return $fullURL;
    }

}