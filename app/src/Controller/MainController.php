<?php
/**
 * Main controller.
 */

namespace App\Controller;

use App\Dto\EventListInputFiltersDto;
use App\Entity\Event;
use App\Service\EventServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController.
 */
#[Route('/')]
class MainController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request                $request       HTTP request
     * @param EventServiceInterface  $eventService  Event service
     * @param PaginatorInterface     $paginator     Paginator
     *
     * @return Response HTTP response
     */
    #[Route('/', name: 'main_index', methods: ['GET'])]
    public function index(Request $request, EventServiceInterface $eventService, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();

        // Filtry dla aktualnych wydarzeń (od teraz do 7 dni wstecz)
        $actualEventFilters = new EventListInputFiltersDto();
        $actualEventFilters->dateFrom = new \DateTime('-7 days');
        $actualEventFilters->dateTo = new \DateTime('now');

        // Pobranie paginacji dla aktualnych wydarzeń
        $actualEventsPagination = $eventService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user,
            $actualEventFilters
        );

        // Filtry dla przyszłych wydarzeń (od teraz do przyszłości)
        $futureEventFilters = new EventListInputFiltersDto();
        $futureEventFilters->dateFrom = new \DateTime('now');

        // Pobranie paginacji dla przyszłych wydarzeń
        $futureEventsPagination = $eventService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user,
            $futureEventFilters
        );

        return $this->render(
            'main/index.html.twig',
            [
                'actualEventsPagination' => $actualEventsPagination,
                'futureEventsPagination' => $futureEventsPagination,
            ]
        );
    }

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