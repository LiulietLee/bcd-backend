<?php


namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(UserManager $userManager, SessionInterface $session) {
        $this->userManager = $userManager;
        $this->session = $session;
    }

    /**
     * disabled
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) {
        $name = $request->request->get("name");
        $password = $request->request->get("password");

        if (
            2 < strlen($name) && strlen($name) < 12 &&
            5 < strlen($password) && strlen($password) < 20
        ) {
            $auth = 1;
            $newUser = $this->userManager->register($name, $password, $auth);
            print_r($newUser);
            return new Response();
        } else {
            return $this->render('register.html.twig');
        }
    }

    /**
     * @Route("/login", name="login")
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) {
        $name = $request->request->get("name");
        $password = $request->request->get("password");

        if (
            2 < strlen($name) && strlen($name) < 12 &&
            5 < strlen($password) && strlen($password) < 20
        ) {
            $user = $this->userManager->login($name, $password);
            if (!$user) return new Response("can't login");

            $this->session->set("user", $user->getID());
            $this->session->set("auth", $user->getAuthority());

            return $this->redirect("/comment");
        } else {
            return $this->render('login.html.twig');
        }
    }
}