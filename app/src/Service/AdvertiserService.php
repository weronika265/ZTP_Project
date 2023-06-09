<?php

/**
 * Advertiser service.
 */

namespace App\Service;

use App\Entity\Advertiser;
use App\Repository\AdvertiserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
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
     * Paginator.
     */
    private PaginatorInterface $paginator;

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
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->advertiserRepository->queryAll(),
            $page,
            AdvertiserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
