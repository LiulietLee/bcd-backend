<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractController extends Controller {

    protected function insert($newRecord) {
        print_r($newRecord);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newRecord);
        $entityManager->flush();
    }

}