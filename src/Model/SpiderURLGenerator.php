<?php

namespace App\Model;

class SpiderURLGenerator {

    private static $base = "https://bilibilicd";

    private static function baseURL(): string {
        $minute = date("i");
        if ($minute < 30) {
            return self::$base. ".vapor.cloud";
        } else {
            return self::$base. ".herokuapp.com";
        }
    }

    static function avCoverInfoURLByAID(int $aid): string {
        $fullURL = self::baseURL(). "/av/info/$aid";
        return $fullURL;
    }

    static function cvCoverInfoURLByCID(int $cid): string {
        $fullURL = self::baseURL(). "/cv/info/$cid";
        return $fullURL;
    }

    static function lvCoverInfoURLByLID(int $lid): string {
        $fullURL = self::baseURL(). "/lv/info/$lid";
        return $fullURL;
    }

}