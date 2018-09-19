<?php

namespace App\Repository;

use App\Entity\Cover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cover|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cover|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cover[]    findAll()
 * @method Cover[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoverRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cover::class);
    }

    /**
     * @param string $stringID
     * @return Cover|null
     */
    public function findOneByStringID(string $stringID) {
        return $this->findOneBy(['strid' =>  $stringID]);
    }

    /**
     * @param string|null $title
     * @param string|null $author
     * @param string|null $stringID
     * @param int $offset
     * @param int $limit
     * @return Cover[]
     */
    public function findCoverByTitleAndAuthorAndStringID(
        string $title = null,
        string $author = null,
        string $stringID = null,
        int $offset = 0,
        int $limit = 20
    ): Array {
        $para = [];
        if ($title) $para["title"] = $title;
        if ($author) $para["author"] = $author;
        if ($stringID) $para["strid"] = $stringID;
        return $this->findBy($para, ["strid" => "ASC"], $limit, $offset);
    }

    public function getCountOfAllCovers(): int {
        $qb = $this->createQueryBuilder('u');
        try {
            return $qb->select("count(u.id)")
                ->getQuery()
                ->getSingleScalarResult();
        } catch(NonUniqueResultException $e) {
            return -1;
        }
    }

    /**
     * @param string $stringID
     * @param string $url
     * @param string $title
     * @param string $author
     * @return Cover
     */
    public function create(
        string $stringID,
        string $url,
        string $title,
        string $author
    ): Cover {
        $newCover = new Cover();
        $newCover->setTitle($title);
        $newCover->setAuthor($author);
        $newCover->setURL($url);
        $newCover->setStringID($stringID);
        return $newCover;
    }

}
