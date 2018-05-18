<?php

namespace App\Controller;

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
     * @param CoverRecord $record
     */
    protected function update(CoverRecord $record) {
        $count = $record->getDownloadCount();
        $record->setDownloadCount($count + 1);
        $zone = new \DateTimeZone("Asia/Shanghai");
        $timeInterface = new \DateTime("now", $zone);
        $record->setTitle($timeInterface);
        $entityManager = $this->entityManager();
        $entityManager->flush();
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