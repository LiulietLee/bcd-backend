<?php

namespace App\Manager;

use App\Entity\Cover;
use App\Entity\Record;
use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;

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
    public function getHotList() {
        $pastDatetime = new \DateTime();
        $pastDatetime->setTimestamp(strtotime("-1 week"));
        $recordList = $this->recordRepository->findRecordsAfterTime($pastDatetime);
        $countList = [];
        foreach ($recordList as $record) {
            if (array_key_exists($record->getStringID(), $countList)) {
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

}