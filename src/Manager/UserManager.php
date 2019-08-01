<?php


namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends AbstractManager {

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager) {
        parent::__construct($entityManager);

        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @param string $name
     * @param string $password
     * @param int $auth
     * @return User
     */
    public function register(string $name, string $password, int $auth) {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        if ($this->userRepository->findBy(["name" => $name], [], 1, 0)) {
            return null;
        }
        $user = $this->userRepository->create($name, $hashedPwd, $auth);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param string $name
     * @param string $password
     * @return User
     */
    public function login(string $name, string $password) {
        $user = $this->userRepository->findBy(["name" => $name], [], 1, 0);

        if (!empty($user))
            $user = $user[0];
        else return null;

        if (password_verify($password, $user->getPassword()))
            return $user;
        else return null;
    }
}