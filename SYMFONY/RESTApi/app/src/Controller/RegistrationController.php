<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use FOS\RestBundle\Controller\Annotations as Rest;

class RegistrationController extends AbstractFOSRestController
{
    private $userRepository;
    private $entityManager;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManagerInterface;
        $factory = new PasswordHasherFactory([
            'JWT' => ['algorithm' => 'argon2i']
        ]);
        $passwordHasher = $factory->getPasswordHasher('JWT');

        $this->passwordHasher = $passwordHasher;
    }
    /**
     * @Rest\Post("/register", name="RegisterUser")
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $user = $this->userRepository->findOneBy([
            "email" => $email,
        ]);

        if (!is_null($user)) {
            return $this->view(["message" => "User with this email already exists."], Response::HTTP_CONFLICT);
        } else {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hash($password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(["public"]));
        }
    }
}
