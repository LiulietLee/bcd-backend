<?php

namespace App\Entity;

class SearchResult {

    protected $stringID;
    protected $url;
    protected $title;
    protected $author;

    public function __construct(\stdClass $jsonData = null) {
        if ($jsonData) {
            $this->setStringID($jsonData->stringID);
            $this->setAuthor($jsonData->author);
            $this->setTitle($jsonData->title);
            $this->setURL($jsonData->url);
        }
    }

    public function getStringID(): string {
        return $this->stringID;
    }

    public function setStringID(string $stringID) {
        $this->stringID = $stringID;
    }

    public function getURL(): string {
        return $this->url;
    }

    public function setURL(string $url) {
        $this->url = $url;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function setAuthor(string $author) {
        $this->author = $author;
    }

    public function stdClass(): \stdClass {
        $result = new \stdClass();
        $result->id = $this->getStringID();
        $result->author = $this->getAuthor();
        $result->title = $this->getTitle();
        $result->url = $this->getURL();
        return $result;
    }

}