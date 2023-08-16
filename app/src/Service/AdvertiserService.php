<?php

/**
 * Advertiser service.
 */

namespace App\Service;

use App\Entity\Advertiser;
use App\Repository\AdvertiserRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdvertiserService.
 */
class AdvertiserService implements AdvertiserServiceInterface
{
    /**
     * Advertiser repository.
     */
    private AdvertiserRepository $advertiserRepository;

    /**
     * Constructor.
     *
     * @param AdvertiserRepository $advertiserRepository Advertiser repository
     * @param PaginatorInterface   $paginator            Paginator
     */
    public function __construct(AdvertiserRepository $advertiserRepository, PaginatorInterface $paginator)
    {
        $this->advertiserRepository = $advertiserRepository;
        $this->paginator = $paginator;
    }

    /**
     * Save entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function save(Advertiser $advertiser): void
    {
        $this->advertiserRepository->save($advertiser);
    }

    /**
     * Save entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function delete(Advertiser $advertiser): void
    {
        $this->advertiserRepository->delete($advertiser);
    }

    /**
     * @param string $email Advertiser email
     *
     * @return Advertiser|null Advertiser entity
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function advertiserEmailExists(string $email): Advertiser|null
    {
        return $this->advertiserRepository->findOneBy(['email' => $email]);
    }
}
