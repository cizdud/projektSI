<?php
/**
 * Event list filters DTO.
 */

namespace App\Dto;

use App\Entity\Category;

/**
 * Class EventListFiltersDto.
 */
class EventListFiltersDto
{
    /**
     * Constructor.
     *
     * @param Category|null $category   Category entity
     */
    public function __construct(public ?Category $category)
    {
    }
}
