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
     * @return Cover[]
     */
    private function getHotListFromDB() {
        $pastDatetime = new \DateTime();
        $pastDatetime->setTimestamp(strtotime("-1 week"));
        $recordList = $this->recordRepository->findRecordsAfterTime($pastDatetime);
        $countList = [];
        foreach ($recordList as $record) {
            if (array_key_exists($record->getStringID(), $countList)) {
                if ($record->getStringID() != "av7")
                    $countList[$record->getStringID()]++;
            } else {
                $countList[$record->getStringID()] = 1;
            }
        }
        arsort($countList);
        $covers = [];
        $counter = 0;
        foreach($countList as $index => $value) {
            $cover = $this->coverRepository->findOneByStringID($index);
            $covers[] = $cover;
            if (++$counter > 10) {
                break;
            }
        }
        return $covers;
    }

    /**
     * @return Cover[]
     */
    public function getHotList() {
        return $this->getHotListFromCache();
    }

}