<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Manager\CommentManager;
use App\Repository\CommentRepository;
use App\Repository\ReplyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReplyController extends BaseController {

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        ReplyRepository $replyRepository, CommentManager $commentManager,
        CommentRepository $commentRepository, SessionInterface $session) {
        $this->replyRepository = $replyRepository;
        $this->commentManager = $commentManager;
        $this->commentRepository = $commentRepository;
        $this->session = $session;
    }

    /**
     * @Route("/comment/{comment}", name="replyIndex")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function index(Comment $comment, Request $request) {
        $auth = $this->session->get("auth", 0);
        if ($auth < 1) return $this->redirect("/login");

        if ($request->query->has("new")) {
            $username = $request->query->get("username", "");
            $content = $request->query->get("content", "");

            if ($username !== "" && $content !== "") {
                $this->commentManager->addReply($comment, $username, $content);
                echo "added<br>";
            }
        } else if ($request->request->has("del")) {
            $replyID = $request->request->getInt("id", -1);
            if ($replyID >= 0) {
                $this->commentManager->deleteReply($replyID);
            }
        } else if ($request->request->has("topChanged")) {
            $newTop = $request->request->getInt("top", 0);
            $this->commentManager->editComment($comment, $newTop);
        }

        $page = $request->query->getInt("page", 0);
        $limit = $request->query->getInt("limit", 20);
        $offset = $page * $limit;
        $list = $this->replyRepository->getReplyWithComment($comment, $offset, $limit);
        $count = $this->replyRepository->getCountOfReplyWithComment($comment);

        return $this->render('reply.html.twig', [
            'comment' => $comment,
            'count' => $count,
            'page' => $page,
            'list' => $list
        ]);
    }

    /**
     * @Route("/api/reply/all/{id}", name="fetchReplies")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function fetch(int $id, Request $request) {
        $page = $request->query->getInt("page", 0);
        $limit = $request->query->getInt("limit", 20);
        $offset = $page * $limit;

        if ($this->needRedirect()) {
            return $this->redirect($this->redirectURL("/api/reply/all/$id?page=$page&limit=$limit"));
        }

        $comment = $this->commentRepository->find($id);
        if ($comment) {
            $count = $this->replyRepository->getCountOfReplyWithComment($comment);
            $result = $this->replyRepository->getReplyWithComment($comment, $offset, $limit);

            $list = $this->listJson($count, $result);
        } else {
            $list = json_encode(["count" => 0, "data" => []]);
        }

        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/reply/new/{id}", name="newReply")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function newReply(int $id, Request $request) {
        $content = $request->getContent();
        if (!empty($content)) {
            $param = json_decode($content, true);

            if ($this->needRedirect()) {
                $res = $this->redirectWithPath("/api/reply/new/$id", 'POST', $param);
                if ($res) return $res;
            }

            $username = $param["username"];
            $content = $param["content"];

            $comment = $this->commentRepository->find($id);
            if ($comment) {
                $reply = $this->commentManager->addReply($comment, $username, $content, true);
                if ($reply) {
                    $result = ["status" => 200, "message" => "OK", "data" => $reply->stdClass()];
                } else {
                    $result = ["status" => 400, "message" => "cannot insert"];
                }
            } else {
                $result = ["status" => 400, "message" => "no comment id $id"];
            }
        } else {
            $result = ["status" => 400, "message" => "empty content"];
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', "application/json");
        return $response;
    }
}