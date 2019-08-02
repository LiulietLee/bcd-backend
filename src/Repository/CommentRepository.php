<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return int
     */
    public function getCountOfAllComments(): int {
        $qb = $this->createQueryBuilder('c');
        try {
            return $qb->select('count(c.id)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Comment[]
     */
    public function fetchComments(int $offset = 0, int $limit = 20): Array {
        return $this->findBy([], ["top" => "DESC", "id" => "DESC"], $limit, $offset);
    }

    /**
     * @param string $username
     * @param string $content
     * @return Comment
     */
    public function create(string $username, string $content): Comment {
        $newComment = new Comment();
        $newComment->setContent($content);
        $newComment->setUsername($username);
        $newComment->setTime(new \DateTime());
        $newComment->setSuki(0);
        $newComment->setKirai(0);
        $newComment->setTop(0);
        $newComment->setReplyCount(0);
        return $newComment;
    }
}
