<?php

namespace App\Controller;

use App\Model\CoverHacker;
use App\Entity\Cover;
use App\Entity\Record;
use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractController extends Controller {

    /**
     * @param string $stringID
     * @param string $title
     * @param string $url
     * @param string $author
     */
    protected function updateOrCreateCover(string $stringID, string $title, string $url, string $author) {
        $cover = $this->coverRepository()->findOneByStringID($stringID);
        if ($cover) {
            $cover->setURL($url);
            $cover->setAuthor($author);
            $cover->setTitle($title);
        } else {
            $cover = $this->coverRepository()->create($stringID, $url, $title, $author);
        }
        $this->insertRecord($stringID);
        $this->entityManager()->persist($cover);
        $this->entityManager()->flush();
    }

    /**
     * @param string $stringID
     * @return Cover|null
     */
    protected function getCoverFromDB(string $stringID) {
        $this->insertRecord($stringID);
        return $this->coverRepository()->findOneByStringID($stringID);
    }

    /**
     * @param string $stringID
     */
    private function insertRecord(string $stringID) {
        $newRecord = $this->recordRepository()->create($stringID);
        $this->entityManager()->persist($newRecord);
        $this->entityManager()->flush();
    }

    /**
     * @return Cover[]
     */
    protected function getHotList() {
        $recordList = $this->recordRepository()->findRecordsAfterTime(time() - 7 * 24 * 60);
        $countList = [];
        foreach ($recordList as $record) {
            if (array_key_exists($record->getStringID(), $countList)) {
                $countList[$record->getStringID()]++;
            } else {
                $countList[$record->getStringID()] = 1;
            }
        }
        sort($countList, SORT_DESC);
        $covers = [];
        $coverRepository = $this->coverRepository();
        foreach($countList as $key => $value) {
            $cover = $coverRepository->findOneByStringID($key);
            $covers[] = $cover;
        }
        return $covers;
    }

    /**
     * @param int $type
     * @param int $nid
     * @return \App\Entity\SearchResult|null
     */
    protected function getCoverFromCoverHacker(int $type, int $nid) {
        $hacker = new CoverHacker();
        $result = $hacker->getCoverByTypeAndNID($type, $nid);
        return $result;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function entityManager() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return CoverRepository
     */
    protected function coverRepository(): CoverRepository {
        return $this->getDoctrine()->getRepository(Cover::class);
    }

    /**
     * @return RecordRepository
     */
    protected function recordRepository(): RecordRepository {
        return $this->getDoctrine()->getRepository(Record::class);
    }

}