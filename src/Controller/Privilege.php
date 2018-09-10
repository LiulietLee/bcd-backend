<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Privilege extends Controller {

    /**
     * @Route("/privilege", name="privilege")
     * @return Response
     */
    public function index() {
        return $this->render('privilege.html.twig', []);
    }
}