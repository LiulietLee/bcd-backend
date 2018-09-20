<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IndexController extends Controller {

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request) {
        $searchText = $request->request->get("searchText");
        if ($searchText) {
            if ($searchText != "") {
                return $this->redirect("/$searchText");
            }
        }

        return $this->render('index.html.twig', []);
    }

}