<?php
/**
 * Event list input filters DTO.
 */

namespace App\Dto;

/**
 * Class EventListInputFiltersDto.
 */
class EventListInputFiltersDto
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId Category identifier
     * @param int      $statusId   Status identifier
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly int $statusId = 1)
    {
    }
}
