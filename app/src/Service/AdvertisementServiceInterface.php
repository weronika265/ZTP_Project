<?php

/**
 * Advertisement service interface.
 */

namespace App\Service;

use App\Entity\Advertisement;
use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AdvertisementServiceInterface.
 */
interface AdvertisementServiceInterface
{
    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function save(Advertisement $advertisement): void;

    /**
     * Delete entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void;

    /**
     * Accept entity as active.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function accept(Advertisement $advertisement): void;

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get paginated list only with unaccepted entities.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListWithUnacceptedEntity(int $page): PaginationInterface;

    /**
     * Get paginated list by category.
     *
     * @param int      $page     Page number
     * @param Category $category Category
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface;
}
