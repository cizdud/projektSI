<?php
/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get paginated list of categories.
     *
     * @param int $page Page number for pagination
     *
     * @return PaginationInterface<string, mixed> Paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save category entity.
     *
     * @param Category $category Category entity to save
     */
    public function save(Category $category): void;

    /**
     * Delete category entity.
     *
     * @param Category $category Category entity to delete
     */
    public function delete(Category $category): void;
}
