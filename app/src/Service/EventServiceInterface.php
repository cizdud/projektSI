<?php
/**
 * Event service interface.
 */

namespace App\Service;

use App\Dto\EventListInputFiltersDto;
use App\Entity\Event;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface EventServiceInterface.
 *
 * Provides methods for managing events.
 */
interface EventServiceInterface
{
    /**
     * Get paginated list of events.
     *
     * @param int                      $page    Page number for pagination
     * @param User                     $author  User who is the author of the events
     * @param EventListInputFiltersDto $filters Filters to apply to the event list
     *
     * @return PaginationInterface<string, mixed> Paginated list of events
     */
    public function getPaginatedList(int $page, User $author, EventListInputFiltersDto $filters): PaginationInterface;

    /**
     * Save event entity.
     *
     * @param Event $event Event entity to save
     */
    public function save(Event $event): void;

    /**
     * Delete event entity.
     *
     * @param Event $event Event entity to delete
     */
    public function delete(Event $event): void;
}
