<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Reply;
use App\Repository\CommentRepository;
use App\Repository\ReplyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager extends AbstractManager {

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var ReplyRepository
     */
    private $replyReposity;

    public function __construct(EntityManagerInterface $entityManager) {
        parent::__construct($entityManager);

        $this->commentRepository = $entityManager->getRepository(Comment::class);
        $this->replyReposity = $entityManager->getRepository(Reply::class);
    }

    /**
     * @param int $commentID
     */
    public function deleteComment(int $commentID) {
        $comment = $this->commentRepository->find($commentID);
        if ($comment) {
            $this->entityManager->remove($comment);
            $replies = $this->replyReposity->getReplyWithComment($comment);
            foreach ($replies as $reply) {
                $this->entityManager->remove($reply);
            }
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
     * @param Comment $comment
     * @return bool
     */
    private function isCommentValid(Comment $comment): bool {
        // TODO: need to check more

        return strlen($comment->getUsername()) < 16
            && strlen($comment->getContent()) < 256;
    }

    /**
     * @param Reply $reply
     * @return bool
     */
    private function isReplyValid(Reply $reply): bool {
        return strlen($reply->getUsername()) < 16
            && strlen($reply->getContent()) < 100;
    }

    /**
     * @param string $username
     * @param string $content
     */
    public function addComment(string $username, string $content) {
        $newComment = $this->commentRepository->create($username, $content);
        if ($this->isCommentValid($newComment)) {
            $this->entityManager->persist($newComment);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Comment $comment
     * @param string $username
     * @param string $content
     */
    public function addReply(Comment $comment, string $username, string $content) {
        $newReply = $this->replyReposity->create($comment, $username, $content);
        if ($this->isReplyValid($newReply)) {
            $this->entityManager->persist($newReply);
            $this->entityManager->flush();
        }
    }

    /**
     * @param int $replyID
     */
    public function deleteReply(int $replyID) {
        $reply = $this->replyReposity->find($replyID);
        if ($reply) {
            $this->entityManager->remove($reply);
            $this->entityManager->flush();
        }
    }
}