<?php

namespace App\Controller;

use App\Dto\AutoMapper\InvitationFormMapper;
use App\Dto\Invitation\InvitationDtoInterface;
use App\Dto\Invitation\InvitationFormDto;
use App\Dto\Invitation\InvitationListDto;
use App\Entity\User;
use App\Events\InvitationEvent;
use App\Exceptions\ValidationFailedException;
use App\Repository\InvitationRepository;
use App\Validator\InvitationFormValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

            $validator->validate($invitation);

            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->dispatcher->dispatch(new InvitationEvent($invitation), InvitationEvent::USER_INVITED);

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

    /**
     * @param int $id
     * @param UserInterface $user
     * @param InvitationRepository $repository
     * @return Response
     */
    #[Route('/invitation/cancel/{id}', name: 'invitation_cancel',  requirements: ['id' => '\d+'], methods: ['PUT'] )]
    public function cancel(int $id, UserInterface $user, InvitationRepository $repository): Response
    {
        try {
            $invitation = $repository->find($id);

            if (! $invitation || $invitation->getInvitedBy() !== $user) {
                throw new NotFoundHttpException();
            }

            if ($invitation->getIsCanceled()) {
                throw new HttpException(Response::HTTP_NO_CONTENT);
            }

            $invitation->setIsCanceled();
            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->dispatcher->dispatch(new InvitationEvent($invitation), InvitationEvent::USER_CANCELED);

            return $this->json([], Response::HTTP_OK);
        } catch (HttpException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @param InvitationRepository $repository
     * @return Response
     */
    #[Route('/invitation/decline/{id}', name: 'invitation_decline',  requirements: ['id' => '\d+'], methods: ['PUT'] )]
    public function decline(int $id, UserInterface $user, InvitationRepository $repository): Response
    {
        try {
            $invitation = $repository->find($id);

            if (
                ! $invitation || $invitation->getInvitedUser() !== $user
                || $invitation->getIsCanceled()
            ) {
                throw new NotFoundHttpException('The event has been canceled or not found!');
            }

            $invitation->setIsDeclined();
            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->dispatcher->dispatch(new InvitationEvent($invitation), InvitationEvent::USER_DECLINED);

            return $this->json([], Response::HTTP_OK);
        } catch (HttpException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @param InvitationRepository $repository
     * @return Response
     */
    #[Route('/invitation/accept/{id}', name: 'invitation_accept',  requirements: ['id' => '\d+'], methods: ['PUT'] )]
    public function accept(int $id, UserInterface $user, InvitationRepository $repository): Response
    {
        try {
            $invitation = $repository->find($id);

            if (
                ! $invitation || $invitation->getInvitedUser() !== $user
                || $invitation->getIsCanceled()
            ) {
                throw new NotFoundHttpException('The event has been canceled or not found!');
            }

            $invitation->setIsAccepted();
            $entityManager = $this->managerRegistry->getManager();

            $entityManager->persist($invitation);
            $entityManager->flush();

            $this->dispatcher->dispatch(new InvitationEvent($invitation), InvitationEvent::USER_DECLINED);

            return $this->json([], Response::HTTP_OK);
        } catch (HttpException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @param User $user
     * @return Response
     */
    #[Route('/invitation/my-list', name: 'invitation_my_invitations', methods: ['GET'])]
    public function myCreatedInvitations(UserInterface $user): Response
    {
        try {
            $invitations = $user->getSentInvitations();
            $mapped = new ArrayCollection();

            foreach ($invitations as $invitation) {
                $mapped->add(InvitationListDto::fromEntity($invitation));
            }

            return $this->json($mapped->toArray(), Response::HTTP_OK);
        }catch (\Throwable) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * @param User $user
     * @return Response
     */
    #[Route('/invitation/invitation-list', name: 'invitation_my_invitation_list', methods: ['GET'])]
    public function myReceivedInvitations(UserInterface $user): Response
    {
        try {
            $invitations = $user->getReceivedInvitations();
            $mapped = new ArrayCollection();

            foreach ($invitations as $invitation) {
                if ($invitation->getIsDeclined()) {
                    continue;
                }

                $mapped->add(InvitationListDto::fromEntity($invitation));
            }

            return $this->json($mapped->toArray(), Response::HTTP_OK);
        }catch (\Throwable $e) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
