<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecordRepository")
 */
class Record
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
     * @ORM\Column(type="datetime")
     */
    private $time;

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
