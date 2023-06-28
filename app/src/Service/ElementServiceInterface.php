<?php
/**
 * Element service interface.
 */

namespace App\Service;

use App\Entity\Element;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ElementServiceInterface.
 */
interface ElementServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int                $page    Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    /**
     * Get paginated list for favourited.
     *
     * @param int                $page    Page number
     * @param User               $user    Favourited by user
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListFav(int $page, User $user, array $filters = []): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void;

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void;
}
