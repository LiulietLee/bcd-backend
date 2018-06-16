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
     * @param Cover $cover
     */
    public function updateOrCreateCover(Cover $cover) {
        $this->updateOrCreateCoverBy(
            $cover->getStringID(),
            $cover->getTitle(),
            $cover->getURL(),
            $cover->getAuthor()
        );
    }
    /**
     * @param string $stringID
     * @param string $title
     * @param string $url
     * @param string $author
     */
    protected function updateOrCreateCoverBy(string $stringID, string $title, string $url, string $author) {
        $cover = $this->coverRepository()->findOneByStringID($stringID);
        if ($cover) {
            $cover->setURL($url);
            $cover->setAuthor($author);
            $cover->setTitle($title);
        } else {
            $cover = $this->coverRepository()->create($stringID, $url, $title, $author);
        }
        $this->insertRecord($cover);
        $this->entityManager()->persist($cover);
        $this->entityManager()->flush();
    }

    /**
     * @param string $stringID
     * @return Cover|null
     */
    protected function getCoverFromDB(string $stringID) {
        $cover = $this->coverRepository()->findOneByStringID($stringID);
        if ($cover) {
            $this->insertRecord($cover);
        }
        return $cover;
    }

    /**
     * @param Cover $cover
     */
    protected function insertRecord(Cover $cover) {
        $newRecord = $this->recordRepository()->create($cover->getStringID());
        $this->entityManager()->persist($newRecord);
        $this->entityManager()->flush();
    }

    /**
     * @return Cover[]
     */
    protected function getHotList() {
        $pastDatetime = new \DateTime();
        $pastDatetime->setTimestamp(strtotime("-1 week"));
        $recordList = $this->recordRepository()->findRecordsAfterTime($pastDatetime);
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
        $coverRepository = $this->coverRepository();
        $counter = 0;
        foreach($countList as $index => $value) {
            $cover = $coverRepository->findOneByStringID($index);
            $covers[] = $cover;
            if (++$counter > 10) {
                break;
            }
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