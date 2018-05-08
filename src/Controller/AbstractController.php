<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\SearchRecord;

class AbstractController extends Controller {

    /**
     * @param $newRecord
     */
    protected function insert($newRecord) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newRecord);
        $entityManager->flush();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function entityManager() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function repository() {
        return $this->getDoctrine()->getRepository(SearchRecord::class);
    }

}