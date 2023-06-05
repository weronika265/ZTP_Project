<?php

/**
 * Advertisement service interface.
 */

namespace App\Service;

use App\Entity\Advertisement;
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

//    TODO: obsluzyc sytuacje, kiedy kategoria jest podpieta do ogloszenia, bo inaczej wywala blad
    /**
     * Delete entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void;
    
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
}
