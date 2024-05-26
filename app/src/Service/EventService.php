<?php
/**
 * Event service.
 */

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class EventService.
 */
class EventService implements EventServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param EventRepository    $eventRepository Event repository
     * @param PaginatorInterface $paginator       Paginator
     */
    public function __construct(private readonly EventRepository $eventRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $author Author
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->eventRepository->queryByAuthor($author),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Event $event Event entity
     */
    public function save(Event $event): void
    {
        $this->eventRepository->save($event);
    }

    /**
     * Delete entity.
     *
     * @param Event $event Event entity
     */
    public function delete(Event $event): void
    {
        $this->eventRepository->delete($event);
    }
}
