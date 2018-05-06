<?php

namespace App\Controller;

use App\Entity\SearchRecord;
use App\Type\CoverType;
use App\Model\CoverHacker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class SearchResultController extends Controller {

    /**
     * @Route('/search/{content}', name='searchContent')
     * @param string $content
     * @param Request $request
     */
    public function index(string $content, Request $request) {
        if (strlen($content) < 3) {
            throw $this->createAccessDeniedException('搜索内容太短啦！');
        }

        $typeString = substr($content, 0, 2);
        $type = CoverType::typeFromString($typeString);
        $nid = substr($content, 2, strlen($content) - 2);
        $hacker = new CoverHacker();
        $result = $hacker->getCoverByTypeAndNID($type, $nid);
        if ($result) {
            // TODO save record to database

            $this->render('result.html.twig', array(
                'title' => $result->getTitle(),
                'author' => $result->getAuthor(),
                'coverURL' => $result->getURL(),
            ));
        } else {
            throw $this->createNotFoundException('找不到封面呢……');
        }
    }

}
