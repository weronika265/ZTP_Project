<?php

/**
 * Advertiser service interface.
 */

namespace App\Service;

use App\Entity\Advertiser;

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
     * Save entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function delete(Advertiser $advertiser): void;

    /**
     * @param string $email Advertiser email
     *
     * @return Advertiser|null Advertiser entity
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function advertiserEmailExists(string $email): Advertiser|null;
}
