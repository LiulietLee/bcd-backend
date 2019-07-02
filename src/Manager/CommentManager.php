<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager extends AbstractManager {

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        parent::__construct($entityManager);

        $this->commentRepository = $entityManager->getRepository(Comment::class);
    }

    /**
     * @param string $username
     * @param string $content
     */
    public function insertComment(string $username, string $content) {
        // TODO: need to check valid
        $newComment = $this->commentRepository->create($username, $content);
        $this->entityManager->persist($newComment);
        $this->entityManager->flush();
    }
}