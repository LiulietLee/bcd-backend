<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class AbstractManager {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

}