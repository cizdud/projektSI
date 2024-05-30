<?php
/**
 * Main controller.
 */

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class MainController.
 */
#[Route('/')]
class MainController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request         HTTP request
     * @param EventRepository    $eventRepository Event repository
     * @param PaginatorInterface $paginator       Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        '/',
        name: 'main_index',
        methods: ['GET']
    )]
    public function index(Request $request, EventRepository $eventRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $eventRepository->findAll(),
            $request->query->getInt('page', 1),
            EventRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'main/index.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
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
        requirements: ['id' => "\d+"],
        methods: ['GET']
    )]
    public function show(Event $event): Response
    {
        return $this->render(
            'event/show.html.twig',
            ['event' => $event]
        );
    }
}
