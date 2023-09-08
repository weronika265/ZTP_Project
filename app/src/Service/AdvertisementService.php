<?php

/**
 * Advertisement service.
 */

namespace App\Service;

use App\Entity\Advertisement;
use App\Entity\Category;
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
     * Accept entity as active.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function accept(Advertisement $advertisement): void
    {
        if (false === $advertisement->isIsActive()) {
            $advertisement->setIsActive(true);
        }

        $this->advertisementRepository->save($advertisement);
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

    /**
     * Get paginated list only with unaccepted entities.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListWithUnacceptedEntity(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->advertisementRepository->getByUnacceptedEntity(),
            $page,
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list only with unaccepted entities.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListWithAcceptedEntity(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->advertisementRepository->getByAcceptedEntity(),
            $page,
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list by category.
     *
     * @param int      $page     Page number
     * @param Category $category Category
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->advertisementRepository->getByCategory($category),
            $page,
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
