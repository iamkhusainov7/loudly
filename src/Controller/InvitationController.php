<?php

namespace App\Controller;

use App\Dto\AutoMapper\InvitationFormMapper;
use App\Dto\Invitation\InvitationDtoInterface;
use App\Dto\Invitation\InvitationFormDto;
use App\Events\InvitationSentEvent;
use App\Exceptions\ValidationFailedException;
use App\Validator\InvitationFormValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class InvitationController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @param Request $request
     * @param UserInterface $user
     * @param InvitationFormValidator $validator
     * @param InvitationFormMapper $mapper
     * @return Response
     */
    #[Route('/invitation/invite', name: 'invitation_invite', methods: ['POST'])]
    public function invite(Request $request, UserInterface $user, InvitationFormValidator $validator, InvitationFormMapper $mapper): Response
    {
        try {
            $data = $request->toArray();

            $data[InvitationDtoInterface::INVITATION_INVITED_BY] = $user;
            $dto = new InvitationFormDto($data);


            $invitation = $mapper->map($dto);

            $invitation->setInvitedBy($user);

            $validator->validate($invitation);

            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->dispatcher->dispatch(new InvitationSentEvent($invitation), InvitationSentEvent::NAME);

            return $this->json([], Response::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'data' => $e->getMessages()->toArray(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
