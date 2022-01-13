<?php

namespace App\Controller\Auth;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class VerifyEmailController extends AbstractController
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private ManagerRegistry            $managerRegistry
    )
    {
    }

    #[Route("/verify", name:"registration_confirmation_route", methods: ['POST'])]
    public function verifyUserEmail(
        Request        $request,
        UserRepository $userRepository
    )
    {
        $id = $request->get('id'); // retrieve the user id from the url

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user || null === $id) {
            return $this->json([
                'message' => 'User does not exist!'
            ]);
        }

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

            if ($user->getIsConfirmed()) {
                return $this->json([
                    'message' => 'The email has been already confirmed!'
                ], 200);
            }

            $entityManager = $this->managerRegistry->getManager();
            $user->setIsConfirmed(true);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'Your email was sucessfully confirmed'
            ]);

        } catch (VerifyEmailExceptionInterface $e) {
            return $this->json([
                'message' => $e->getReason()
            ], 400);
        }
    }
}
