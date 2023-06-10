<?php

/**
 * Advertiser service interface.
 */

namespace App\Service;

use App\Entity\Advertiser;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AdvertiserServiceInterface.
 */
interface AdvertiserServiceInterface
{
    /**
     * Save entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function save(Advertiser $advertiser): void;

    /**
     * Delete entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function delete(Advertiser $advertiser): void;

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
}
