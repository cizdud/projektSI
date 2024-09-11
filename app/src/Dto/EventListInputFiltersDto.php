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
     * @param int|null                $categoryId Category identifier
     * @param \DateTimeImmutable|null $dateFrom   Starting date
     * @param \DateTimeImmutable|null $dateTo     Ending date
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly ?\DateTimeImmutable $dateFrom = null, public readonly ?\DateTimeImmutable $dateTo = null)
    {
    }
}
