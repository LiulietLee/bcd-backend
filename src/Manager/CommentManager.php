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
     * @return Comment
     */
    public function addComment(string $username, string $content): Comment {
        $newComment = $this->commentRepository->create($username, $content);
        if ($this->isCommentValid($newComment)) {
            $this->entityManager->persist($newComment);
            $this->entityManager->flush();
            return $newComment;
        } else {
            return null;
        }
    }

    /**
     * @param Comment $comment
     * @param int $acc
     */
    public function changeLikeOfComment(Comment $comment, int $acc) {
        if ($comment) {
            $newValue = $comment->getSuki() + $acc;
            $newValue = max(min($newValue, 99999), 0);
            $comment->setSuki($newValue);
            $this->entityManager->flush();
            print_r($acc);
        }
    }

    /**
     * @param Comment $comment
     * @param int $acc
     */
    public function changeDislikeOfComment(Comment $comment, int $acc) {
        if ($comment) {
            $newValue = $comment->getKirai() + $acc;
            $newValue = max(min($newValue, 99999), 0);
            $comment->setKirai($newValue);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Comment $comment
     * @param string $username
     * @param string $content
     * @return Reply
     */
    public function addReply(Comment $comment, string $username, string $content): Reply {
        $newReply = $this->replyReposity->create($comment, $username, $content);
        if ($this->isReplyValid($newReply)) {
            $this->entityManager->persist($newReply);
            $this->entityManager->flush();
            return $newReply;
        } else {
            return null;
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