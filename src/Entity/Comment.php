<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $suki;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $kirai;

    /**
     * @ORM\Column(type="smallint")
     */
    private $top;

    /**
     * @ORM\Column(type="smallint")
     */
    private $replyCount;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function getSuki(): ?int
    {
        return $this->suki;
    }

    public function setSuki(?int $suki): self
    {
        $this->suki = $suki;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getKirai(): ?int
    {
        return $this->kirai;
    }

    public function setKirai(?int $kirai): self
    {
        $this->kirai = $kirai;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function stdClass(): \stdClass {
        $result = new \stdClass();
        $result->id = $this->getId();
        $result->username = $this->getUsername();
        $result->content = $this->getContent();
        $result->suki = $this->getSuki();
        $result->kirai = $this->getKirai();
        $result->time = $this->getTime()->format('c');
        $result->top = $this->getTop();
        $result->replyCount = $this->getReplyCount();
        return $result;
    }

    public function getTop(): ?int
    {
        return $this->top;
    }

    public function setTop(int $top): self
    {
        $this->top = $top;

        return $this;
    }

    public function getReplyCount(): ?int
    {
        return $this->replyCount;
    }

    public function setReplyCount(int $replyCount): self
    {
        $this->replyCount = $replyCount;

        return $this;
    }
}
