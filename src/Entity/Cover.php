<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoverRepository")
 */
class Cover
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $strid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    public function getId()
    {
        return $this->id;
    }

    public function getStringID(): ?string
    {
        return $this->strid;
    }

    public function setStringID(string $strid): self
    {
        $this->strid = $strid;

        return $this;
    }

    public function getURL(): ?string
    {
        return $this->url;
    }

    public function setURL(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function stdClass(): \stdClass {
        $result = new \stdClass();
        $result->id = $this->getStringID();
        $result->title = $this->getTitle();
        $result->author = $this->getAuthor();
        $result->url = $this->getURL();
        return $result;
    }
}
