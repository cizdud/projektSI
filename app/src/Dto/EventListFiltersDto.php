<?php
/**
 * Event list filters Dto.
 */

namespace App\Dto;

use App\Entity\Category;

/**
 * Class EventListFiltersDto.
 */
class EventListFiltersDto
{
    public ?Category $category;
    public ?\DateTimeInterface $dateFrom;
    public ?\DateTimeInterface $dateTo;

    /**
     * Constructor.
     *
     * @param Category|null           $category Category entity
     * @param \DateTimeInterface|null $dateFrom Start date
     * @param \DateTimeInterface|null $dateTo   End date
     */
    public function __construct(?Category $category = null, ?\DateTimeInterface $dateFrom = null, ?\DateTimeInterface $dateTo = null)
    {
        $this->category = $category;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
}
