<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Manager\CommentManager;
use App\Repository\ReplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReplyController extends AbstractController {

    /**
     * @var ReplyRepository
     */
    private $replyRepository;

    /**
     * @var CommentManager
     */
    private $commentManager;

    public function __construct(ReplyRepository $replyRepository, CommentManager $commentManager) {
        $this->replyRepository = $replyRepository;
        $this->commentManager = $commentManager;
    }

    /**
     * @Route("/comment/{comment}", name="replyIndex")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function index(Comment $comment, Request $request) {
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
     * @Route("/api/reply/all/{comment}", name="fetchReplies")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function fetch(Comment $comment, Request $request) {
        $page = $request->query->getInt("page", 0);
        $limit = $request->query->getInt("limit", 20);
        $offset = $page * $limit;

        $result = $this->replyRepository->getReplyWithComment($comment, $offset, $limit);
        $list = [];
        foreach ($result as $item) {
            $listItem = $item->stdClass();
            $list[] = $listItem;
        }

        $list = json_encode($list);
        $response = new Response($list);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/reply/new/{comment}", name="newReply")
     *
     * @param Comment $comment
     * @param Request $request
     * @return Response
     */
    public function newReply(Comment $comment, Request $request) {
        $content = $request->getContent();
        if (!empty($content)) {
            $param = json_decode($content);

            $username = $param['username'];
            $content = $param['content'];

            $this->commentManager->addReply($comment, $username, $content);
            $result = ["status" => 200, "message" => "OK"];
        } else {
            $result = ["status" => 500, "message" => "empty content"];
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', "application/json");
        return $response;
    }
}