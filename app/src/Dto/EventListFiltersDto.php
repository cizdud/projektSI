<?php
/**
 * Event list filters DTO.
 */

namespace App\Dto;

use App\Entity\Category;
use App\Entity\Enum\EventStatus;

/**
 * Class EventListFiltersDto.
 */
class EventListFiltersDto
{
    /**
     * Constructor.
     *
     * @param Category|null $category   Category entity
     * @param EventStatus    $eventStatus Event status
     */
    public function __construct(public ?Category $category, public EventStatus $eventStatus)
    {
    }
}
