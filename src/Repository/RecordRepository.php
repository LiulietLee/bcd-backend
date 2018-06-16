<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
