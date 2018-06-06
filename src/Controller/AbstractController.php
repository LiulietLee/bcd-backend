<?php

namespace App\Controller;

use App\Model\CoverHacker;
use App\Repository\CoverRecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\CoverRecord;

class AbstractController extends Controller {

    /**
     * @param CoverRecord $newRecord
     */
    protected function insert(CoverRecord $newRecord) {
        $entityManager = $this->entityManager();
        $entityManager->persist($newRecord);
        $entityManager->flush();
    }

    /**
     * 如果发现上次下载是在一天之前则再次下载更新数据并返回 true。
     * 如果上次下载是在一天之内或者发现视频封面无法获取则返回 false 并更新下载次数与时间。
     *
     * @param CoverRecord $record
     * @return Bool
     */
    protected function update(CoverRecord $record): Bool {
        $count = $record->getDownloadCount();
        $timeInterface = new \DateTime();

        $previousSearchingTime = $record->getTime();
        $interval = $timeInterface->getTimestamp() - $previousSearchingTime->getTimestamp();
        if ($interval > 24 * 60 * 60) {
            $newCover = $this->getCoverFromCoverHacker($record->getType(), $record->getNID());
            if (!property_exists($newCover, "error")) {
                $this->entityManager()->remove($record);
                $this->entityManager()->flush();
                $record = $this->coverRecordRepository()->create(
                    $record->getType(),
                    $newCover->getURL(),
                    $record->getNID(),
                    $newCover->getTitle(),
                    $newCover->getAuthor()
                );
                $record->setDownloadCount($count + 1);
                $this->insert($record);
                return true;
            }
        }

        $record->setDownloadCount($count + 1);
        $record->setTime($timeInterface);
        $this->entityManager()->flush();
        return false;
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
     * @return CoverRecordRepository
     */
    protected function coverRecordRepository(): CoverRecordRepository {
        return $this->getDoctrine()->getRepository(CoverRecord::class);
    }

}