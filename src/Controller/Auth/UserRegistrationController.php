<?php

namespace App\Controller\Auth;

use App\Dto\AutoMapper\UserRegistrationMapper;
use App\Dto\User\UserRegistrationDto;
use App\Events\UserRegisteredEvent;
use App\Exceptions\ValidationFailedException;
use App\Validator\UserRegistrationFormValidator;
use App\Validator\ValidatorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserRegistrationController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private ValidatorInterface $validator,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    #[Route('/auth/user/registration', name: 'auth_user_registration', methods: ['POST'])]
    public function create(Request $request, UserRegistrationMapper $mapper): Response
    {
        try {
            $userDto = new UserRegistrationDto($request->toArray());
            $user = $mapper->map($userDto);

            $this->validator->validate(
                new ArrayCollection([
                    UserRegistrationFormValidator::USER_KEY => $user,
                    UserRegistrationDto::USER_PASSWORD_CONFIRMATION => $userDto->userPasswordConfirm
                ])
            );

            $entityManager = $this->managerRegistry->getManager();
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $user->getPassword())
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $registrationLink = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            )->getSignedUrl();

            $this->dispatcher->dispatch(new UserRegisteredEvent($user, $registrationLink), UserRegisteredEvent::NAME);

            return $this->json([
                'message' => 'You have been successfully registered! Please, check your email and confirm your email!',
            ], Response::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'data' => $e->getMessages()->toArray(),
            ], $e->getStatusCode());
        }
    }
}
