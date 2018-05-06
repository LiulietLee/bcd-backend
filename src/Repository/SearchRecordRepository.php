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
        $this->repository = $this->getD;
    }

    public function findByTypeAfterTime(CoverType $type, int $time) {

    }

//    /**
//     * @return SearchRecord[] Returns an array of SearchRecord objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchRecord
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
