<?php

namespace App\Controller;

use App\Manager\CommentManager;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController {

    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * @var CommentRepository;
     */
    private $commentRepository;

    public function __construct(CommentManager $commentManager, CommentRepository $commentRepository) {
        $this->commentManager = $commentManager;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/comment", name="commentPage")
     *
     * @param Request $request
     * @return Response
     */
    function index(Request $request) {
        $username = $request->query->get("username", "");
        $content = $request->query->get("content", "");

        if ($username !== "" || $content !== "") {
            $this->commentManager->insertComment($username, $content);
            echo "inserted<br>";
        }

        $page = $request->query->getInt("page", 0);
        $limit = 20;
        $offset = $page * $limit;
        $list = $this->commentRepository->fetchComments($offset, $limit);
        $count = $this->commentRepository->getCountOfAllComments();

        return $this->render('comment.html.twig', [
            'count' => $count,
            'page' => $page,
            'list' => $list
        ]);
    }

    /**
     * @Route("/api/comment/new", name="newComment")
     *
     * @param Request $request
     * @return Response
     */
    function addComment(Request $request): Response {
        $comment = $request->getContent();
        if (!empty($comment)) {
            $param = json_decode($comment, true);

            $username = $param["username"];
            $content = $param["content"];

            $this->commentManager->insertComment($username, $content);
            $result = ["status" => 200, "message" => "OK"];
        } else {
            $result = ["status" => 500, "message" => "empty content"];
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}