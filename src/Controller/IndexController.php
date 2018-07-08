<?php

namespace App\Controller;

use App\Entity\SearchContent;
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
        $searchContent = new SearchContent();

        $form = $this->createFormBuilder($searchContent)
            ->add('content', TextType::class)
            ->add('search', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content = $form->getData()->getContent();
            if ($content != "") {
                return $this->redirect("/$content");
            }
        }

        return $this->render('index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}