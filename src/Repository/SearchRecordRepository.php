<?php

namespace App\Repository;

use App\Entity\SearchRecord;
use App\Type\CoverType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @method SearchRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchRecord[]    findAll()
 * @method SearchRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchRecordRepository extends ServiceEntityRepository
{
    protected $repository;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SearchRecord::class);
    }

    /**
     * @param int|null $type
     * @param int $time
     * @param int $limit
     * @return SearchRecord[]|null
     */
    public function findByTypeAfterTime(?int $type = null, int $time, int $limit = 10) {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.time > :time')->setParameter('time', $time);
        if ($type) $qb->andWhere('p.type = :type')->setParameter('type', $type);
        $result = $qb->orderBy('p.download_count', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param int $type
     * @param int $nid
     * @return SearchRecord|null
     */
    public function findOnyByTypeAndNID(int $type, int $nid) {
        return $this->findOneBy(['type' => $type, 'nid' => $nid]);
    }

}
