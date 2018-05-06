<?php

namespace App\Entity;

class SearchResult {

    protected $url;
    protected $title;
    protected $author;

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

}