<?php

namespace App\Manager;

use App\Entity\Cover;
use App\Entity\Record;
use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Cache\InvalidArgumentException;

class HotListManager extends AbstractManager {

    /**
     * @var CoverRepository
     */
    private $coverRepository;

    /**
     * @var RecordRepository
     */
    private $recordRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        parent::__construct($entityManager);

        $this->coverRepository = $entityManager->getRepository(Cover::class);
        $this->recordRepository = $entityManager->getRepository(Record::class);
    }

    /**
     * @return Cover[]
     */
    private function getHotListFromCache() {
        $cache = new FilesystemAdapter();
        try {
            $result = $cache->getItem('hotList');
        } catch (InvalidArgumentException $e) {
            return [];
        }
        if ($result->isHit()) {
            return $result->get();
        } else {
            $list = $this->getHotListFromDB();
            $result->set($list);
            $result->expiresAfter(600);
            $cache->save($result);
            return $list;
        }
    }

    /**
     * Raw SQL:
     * 
     * select C.*
     * from record as R, cover as C
     * where time > now() - interval 1 week and C.strid = R.strid 
     * group by C.id 
     * order by count(C.id) desc 
     * limit 10;   
     * 
     * @return Cover[]
     */
    private function getHotListFromDB() {
        $pastDatetime = new \DateTime();
        $pastDatetime->setTimestamp(strtotime("-1 week"));

        $query = $this->entityManager
            ->createQuery(
                'SELECT C
                FROM App\Entity\Record R, App\Entity\Cover C
                WHERE R.time > :pastDatetime and C.strid = R.strid 
                GROUP BY C.id 
                ORDER BY COUNT(C.id) DESC'
            )
            ->setParameter('pastDatetime', $pastDatetime)
            ->setMaxResults(12);

        return $query->getResult();
    }

    /**
     * @return Cover[]
     */
    public function getHotList() {
        return $this->getHotListFromCache();
    }

}