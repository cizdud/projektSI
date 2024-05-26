<?php
/**
 * Event controller.
 */

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\Type\EventType;
use App\Service\EventServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EventController.
 */
#[Route('/event')]
class EventController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param EventServiceInterface $eventService Event service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(private readonly EventServiceInterface $eventService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'event_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->eventService->getPaginatedList(
            $page,
            $this->getUser()
        );

        return $this->render('event/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Event $event Event entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'event_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(Event $event): Response
    {
        if ($event->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/show.html.twig',
            ['event' => $event]
        );
    }


    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'event_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $event = new Event();
        $event->setAuthor($user);
        $form = $this->createForm(
            EventType::class,
            $event,
            ['action' => $this->generateUrl('event_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->save($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Event $event Event entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'event_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Event $event): Response
    {
        if ($event->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('event_index');
        }

        $form = $this->createForm(
            EventType::class,
            $event,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('event_edit', ['id' => $event->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->save($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/edit.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Event $event Event entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'event_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Event $event): Response
    {
        if ($event->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('event_index');
        }

        $form = $this->createForm(
            FormType::class,
            $event,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('event_delete', ['id' => $event->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->delete($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/delete.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }
}