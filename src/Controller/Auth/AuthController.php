<?php

namespace App\Controller\Auth;

use App\Dto\User\UserLoginDto;
use App\Exceptions\LoginFailedException;
use App\Validator\UserLoginFormValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    #[Route('/auth/user/login', name: 'auth_user_login', methods: ['POST'])]
    public function login(Request $request, UserLoginFormValidator $validator): Response
    {
        try {
            $userDto = new UserLoginDto($request->toArray());
            $validator->validate($userDto);

            $user = $validator->getValidated();
            $user->setIsConfirmed(true);
            $entityManager = $this->managerRegistry->getManager();

            $user->setApiToken(sha1(random_bytes(20)));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'api_token' =>  $user->getApiToken(),
            ], Response::HTTP_OK);
        } catch (LoginFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    #[Route('/auth/user/logout', name: 'auth_user_out', methods: ['DELETE'])]
    public function logout(UserInterface $user): Response
    {
        try {
            $user->setApiToken(null);

            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
