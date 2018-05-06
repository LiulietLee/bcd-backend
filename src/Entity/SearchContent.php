<?php

namespace App\Entity;

class SearchContent {

    protected $content;

    public function getContent() {
        return $this->content;
    }

    public function setContent($value) {
        $this->content = $value;
    }

}