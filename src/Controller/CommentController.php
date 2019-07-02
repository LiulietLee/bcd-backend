<?php

namespace App\Controller;

use App\Manager\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController {

    /**
     * @var CommentManager
     */
    private $commentManager;

    public function __construct(CommentManager $commentManager) {
        $this->commentManager = $commentManager;
    }

    /**
     * @Route("api/comment/new", name="newComment")
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