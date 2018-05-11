<?php

namespace App\Entity;

use App\Type\CoverType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoverRecordRepository")
 */
class CoverRecord
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $nid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $dlcount;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNID(): ?int
    {
        return $this->nid;
    }

    public function setNID(int $nid): self
    {
        $this->nid = $nid;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

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

    public function getDownloadCount(): ?int
    {
        return $this->dlcount;
    }

    public function setDownloadCount(int $dlcount): self
    {
        $this->dlcount = $dlcount;

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

    public function getStringID(): ?string {
        $id = "";
        switch ($this->type) {
            case CoverType::Video:
                $id .= "av";
                break;
            case CoverType::Article:
                $id .= "cv";
                break;
            case CoverType::Live:
                $id .= "lv";
                break;
            default:
                return null;
        }
        $id .= $this->nid;
        return $id;
    }
}
