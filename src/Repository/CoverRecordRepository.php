<?php

namespace App\Repository;

use App\Entity\CoverRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CoverRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoverRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoverRecord[]    findAll()
 * @method CoverRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoverRecordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CoverRecord::class);
    }

    /**
     * @param int|null $type
     * @param int $time
     * @param int $limit
     * @return CoverRecord[]
     */
    public function findByTypeAfterTime(?int $type = null, int $time, int $limit = 10) {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.time > :time')->setParameter('time', $time);
        if ($type) $qb->andWhere('p.type = :type')->setParameter('type', $type);
        $result = $qb->orderBy('p.dlcount', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @param int $type
     * @param int $nid
     * @return CoverRecord|null
     */
    public function findOnyByTypeAndNID(int $type, int $nid) {
        return $this->findOneBy(['type' => $type, 'nid' => $nid]);
    }

    /**
     * @param int $type
     * @param string $url
     * @param int $nid
     * @return CoverRecord
     */
    public function create(int $type, string $url, int $nid) {
        $record = new CoverRecord();

        $zone = new \DateTimeZone("	Asia/Shanghai");
        $timeInterface = new \DateTime("now", $zone);
        $record->setTime($timeInterface);
        $record->setType($type);
        $record->setUrl($url);
        $record->setDlcount(1);
        $record->setNid($nid);

        return $record;
    }

}
