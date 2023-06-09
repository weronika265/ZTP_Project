<?php

/**
 * Advertisement service.
 */

namespace App\Service;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AdvertisementService.
 */
class AdvertisementService implements AdvertisementServiceInterface
{
    /**
     * Advertisement repository.
     */
    private AdvertisementRepository $advertisementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param AdvertisementRepository $advertisementRepository Advertisement repository
     * @param PaginatorInterface      $paginator               Paginator
     */
    public function __construct(AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator)
    {
        $this->advertisementRepository = $advertisementRepository;
        $this->paginator = $paginator;
    }

    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function save(Advertisement $advertisement): void
    {
        if (null == $advertisement->getId()) {
            $advertisement->setDate(new \DateTimeImmutable());
        }

        $this->advertisementRepository->save($advertisement);
//        TODO: nowe ogloszenie - is_active = 0, zmiana przez admina
    }

    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void
    {
        $this->advertisementRepository->delete($advertisement);
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
            $this->advertisementRepository->queryAll(),
            $page,
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
