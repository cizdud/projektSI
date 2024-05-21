<?php
/**
 * Event controller.
 */

namespace App\Controller;

use App\Entity\Event;
use App\Service\EventService;
use App\Service\EventServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class EventController.
 */
#[Route('/event')]
class EventController extends AbstractController
{
    /**
     * Constructor.
     */
    public function __construct(private readonly EventServiceInterface $eventService)
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
        $pagination = $this->eventService->getPaginatedList($page);

        return $this->render('event/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Event $event Event
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'event_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', ['event' => $event]);
    }
}
