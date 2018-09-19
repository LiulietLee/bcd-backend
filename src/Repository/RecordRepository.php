<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Record::class);
    }

    /**
     * @param \DateTime $time
     * @return Record[]
     */
    public function findRecordsAfterTime(\DateTime $time) {
        return $this->createQueryBuilder('p')
            ->where('p.time > :time')
            ->setParameter('time', $time)
            ->orderBy('p.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Record[]
     */
    public function getRecord(int $offset, int $limit): Array {
        return $this->findBy([], ["id" => "ASC"], $limit, $offset);
    }

    /**
     * @return int
     */
    public function getCountOfAllRecords(): int {
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
     * @return Record
     */
    public function create(string $stringID): Record {
        $newRecord = new Record();
        $newRecord->setStringID($stringID);
        $newRecord->setTime(new \DateTime());
        return $newRecord;
    }

}
