<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Reply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reply[]    findAll()
 * @method Reply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reply::class);
    }

    /**
     * @param Comment $comment
     * @return int
     */
    public function getCountOfReplyWithComment(Comment $comment): int {
        $qb = $this->createQueryBuilder('r');
        try {
            return $qb->select('count(r.id)')
                ->andWhere('r.commentID = :commentID')
                ->setParameter('commentID', $comment->getId())
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
    }

    /**
     * @param Comment $comment
     * @param int $offset
     * @param int $limit
     * @return Reply[]
     */
    public function getReplyWithComment(Comment $comment, int $offset = null, int $limit = null): Array {
        return $this->findBy(['commentID' => $comment->getId()], [], $limit, $offset);
    }

    /**
     * @param Comment $comment
     * @param string $username
     * @param string $content
     * @return Reply
     */
    public function create(Comment $comment, string $username, string $content): Reply {
        $newReply = new Reply();
        $newReply->setUsername($username);
        $newReply->setContent($content);
        $newReply->setCommentID($comment->getId());
        $newReply->setTime(new \DateTime());
        return $newReply;
    }
}
