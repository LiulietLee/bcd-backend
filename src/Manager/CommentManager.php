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
     * @param int $commentID
     */
    public function deleteComment(int $commentID) {
        $comment = $this->commentRepository->find($commentID);
        if (!$comment) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
        }
    }

    /**
     * @param int $commentID
     * @param string|null $newContent
     * @param string|null $newUsername
     */
    public function editComment(int $commentID, string $newContent = null, string $newUsername = null) {
        $comment = $this->commentRepository->find($commentID);
        if (!$newContent) $comment->setContent($newContent);
        if (!$newUsername) $comment->setUsername($newUsername);
        $this->entityManager->flush();
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