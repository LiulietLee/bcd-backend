<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BaseController extends AbstractController
{
    /**
     * @return bool
     */
    protected function needRedirect(): bool {
        return getenv('need_redirect') == 'true';
    }

    /**
     * @param string $path
     * @return string
     */
    protected function redirectURL(string $path = ''): string {
        return 'http://'. getenv('redirect_host'). $path;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $param
     * @return Response|null
     */
    protected function redirectWithPath(string $path, string $method = 'GET', array $param = []) {
        $url = $this->redirectURL($path);
        $client = HttpClient::create();
        try {
            $res = $client->request($method, $url, [
                'json' => $param,
            ]);
            $content = $res->toArray();
            $response = new Response(json_encode($content));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (ExceptionInterface $e) {
            return null;
        }
    }

    /**
     * @param int $count
     * @param array $data
     * @return string
     */
    protected function listJson(int $count, array $data): string {
        $list = [];
        foreach ($data as $item) {
            $listItem = $item->stdClass();
            $list[] = $listItem;
        }

        return json_encode(["count" => $count, "data" => $list]);
    }
}