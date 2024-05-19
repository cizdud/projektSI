<?php
/**
 * Event controller.
 */

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class EventController.
 */
#[Route('/event')]
class EventController extends AbstractController
{
    /**
     * Index action.
     *
     * @param EventRepository     $eventRepository Event repository
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     */
    #[Route(name: 'event_index', methods: 'GET')]
    public function index(EventRepository $eventRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $paginator->paginate(
            $eventRepository->queryAll(),
            $page,
            EventRepository::PAGINATOR_ITEMS_PER_PAGE
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
    #[Route(
        '/{id}',
        name: 'event_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Event $event): Response
    {
        return $this->render(
            'event/show.html.twig',
            ['event' => $event]
        );
    }
}
