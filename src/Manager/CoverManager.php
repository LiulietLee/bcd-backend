<?php

namespace App\Manager;

use App\Entity\Cover;
use App\Entity\Record;
use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\EntityManagerInterface;

class CoverManager extends AbstractManager {

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
    public function updateOrCreateCoverBy(string $stringID, string $title, string $url, string $author) {
        $cover = $this->coverRepository->findOneByStringID($stringID);
        if ($cover) {
            $cover->setURL($url);
            $cover->setAuthor($author);
            $cover->setTitle($title);
        } else {
            $cover = $this->coverRepository->create($stringID, $url, $title, $author);
        }
        $this->insertRecord($cover);
        $this->entityManager->persist($cover);
        $this->entityManager->flush();
    }

    /**
     * @param string $stringID
     * @return Cover|null
     */
    public function getCoverFromDB(string $stringID) {
        $cover = $this->coverRepository->findOneByStringID($stringID);
        if ($cover) {
            $this->insertRecord($cover);
        }
        return $cover;
    }

    /**
     * @param Cover $cover
     */
    public function insertRecord(Cover $cover) {
        $newRecord = $this->recordRepository->create($cover->getStringID());
        $this->entityManager->persist($newRecord);
        $this->entityManager->flush();
    }

}