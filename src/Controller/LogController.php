<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Repository\CoverRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends Controller {

    /**
     * @var CoverRepository
     */
    private $coverRepository;

    /**
     * @var RecordRepository
     */
    private $recordRepository;

    public function __construct(CoverRepository $coverRepository, RecordRepository $recordRepository) {
        $this->coverRepository = $coverRepository;
        $this->recordRepository = $recordRepository;
    }

    /**
     * @Route("/log/cover", name="coverLog")
     * @param Request $request
     * @return Response
     */
    public function coverLog(Request $request) {
        $page = $request->query->getInt("page", 0);
        $limit = 20;
        $offset = $page * $limit;
        if ($page < 0) {
            $page = 0;
        }

        $searchContent = new Cover();
        $form = $this->createFormBuilder($searchContent)
            ->add('title', TextType::class, ['required' => false])
            ->add('author', TextType::class, ['required' => false])
            ->add('stringID', TextType::class, [
                'label' => 'String ID',
                'required' => false
            ])
            ->add('search', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Cover **/
            $data = $form->getData();
            $title = $data->getTitle();
            $author = $data->getAuthor();
            $stringID = $data->getStringID();
        } else {
            $title = $author = $stringID = null;
        }

        $result = $this->coverRepository->findCoverByTitleAndAuthorAndStringID($title, $author, $stringID, $offset, $limit);
        $list = [];
        foreach ($result as $item) {
            $newItem = $item->stdClass();
            $list[] = $newItem;
        }

        return $this->render('coverLog.html.twig', array(
            'search' => $form->createView(),
            'page' => $page,
            'list' => $list
        ));
    }

}
