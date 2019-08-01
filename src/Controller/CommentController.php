<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Manager\CommentManager;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        CommentManager $commentManager, CommentRepository $commentRepository, SessionInterface $session) {
        $this->commentManager = $commentManager;
        $this->commentRepository = $commentRepository;
        $this->session = $session;
    }

    /**
     * @Route("/comment", name="commentPage")
     *
     * @param Request $request
     * @return Response
     */
    function index(Request $request) {
        $auth = $this->session->get("auth", 0);
        if ($auth < 1) return $this->redirect("/login");

        if ($request->query->has("new")) {
            $username = $request->query->get("username", "");
            $content = $request->query->get("content", "");

            if ($username !== "" || $content !== "") {
                $this->commentManager->addComment($username, $content);
                echo "inserted<br>";
            }
        } else if ($request->request->has("del")) {
            $commentID = $request->request->getInt("id", -1);
            if ($commentID >= 0) {
                $this->commentManager->deleteComment($commentID);
            }
        }

        $page = $request->query->getInt("page", 0);
        $limit = $request->query->getInt("limit", 20);
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

            $comment = $this->commentManager->addComment($username, $content, true);
            if ($comment) {
                $result = ["status" => 200, "message" => "OK", "data" => $comment->stdClass()];
            } else {
                $result = ["status" => 400, "message" => "cannot insert"];
            }
        } else {
            $result = ["status" => 400, "message" => "empty content"];
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/comment/all", name="allComment")
     *
     * @param Request $request
     * @return Response
     */
    function fetchComment(Request $request): Response {
        $page = $request->query->getInt("page");
        $limit = $request->query->getInt("limit");

        $count = $this->commentRepository->getCountOfAllComments();
        $result = $this->commentRepository->fetchComments($page * $limit, $limit);
        $list = [];
        foreach ($result as $item) {
            $listItem = $item->stdClass();
            $list[] = $listItem;
        }

        $list = json_encode(["count" => $count, "data" => $list]);
        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/comment/like/{comment}", name="likeComment")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    function like(Comment $comment, Request $request) {
        if ($comment && $request->query->has("cancel")) {
            $acc = $request->query->getBoolean("cancel", true) ? -1 : 1;
            $this->commentManager->changeLikeOfComment($comment, $acc);
            $list = ["status" => 200, "message" => "OK"];
        } else {
            $list = ["status" => 500, "message" => "wrong api"];
        }

        $response = new Response(json_encode($list));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/comment/dislike/{comment}", name="dislikeComment")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    function dislike(Comment $comment, Request $request) {
        if ($comment && $request->query->has("cancel")) {
            $acc = $request->query->getBoolean("cancel", true) ? -1 : 1;
            $this->commentManager->changeDislikeOfComment($comment, $acc);
            $list = ["status" => 200, "message" => "OK"];
        } else {
            $list = ["status" => 500, "message" => "wrong api"];
        }

        $response = new Response(json_encode($list));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}