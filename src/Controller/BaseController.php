<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BaseController extends AbstractController
{
    /**
     * @return bool
     */
    protected function needRedirect(): bool {
        return getenv('need_redirect') == true;
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
    protected function redirectWithPath(string $path, string $method = 'GET', array $param = []): ?Response {
        $url = $this->redirectURL($path);
        $client = HttpClient::create();
        try {
            return $client->request($method, $url, $param);
        } catch (TransportExceptionInterface $e) {
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