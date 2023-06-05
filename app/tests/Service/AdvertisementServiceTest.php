<?php

/**
 * Advertisement service tests.
 */

namespace App\Tests\Service;

use App\Entity\Advertisement;
use App\Service\AdvertisementService;
use App\Service\AdvertisementServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AdvertisementServiceTest.
 */
class AdvertisementServiceTest extends KernelTestCase
{
    /**
     * Advertisement repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Advertisement service.
     */
    private ?AdvertisementServiceInterface $advertisementService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->advertisementService = $container->get(AdvertisementService::class);
    }

    /**
     * Test save.
     *
     * @throws NoResultException|NonUniqueResultException
     */
    public function testSave(): void
    {
        // given
        $expectedAdvertisement = new Advertisement();
        $expectedAdvertisement->setName('Test Advertisement - save');

        // when
        $this->advertisementService->save($expectedAdvertisement);

        // then
        $expectedAdvertisementId = $expectedAdvertisement->getId();
        $resultAdvertisement = $this->entityManager->createQueryBuilder()
            ->select('advertisement')
            ->from(Advertisement::class, 'advertisement')
            ->where('advertisement.id = :id')
            ->setParameter(':id', $expectedAdvertisementId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedAdvertisement, $resultAdvertisement);
    }

    /**
     * Test delete.
     *
     * @throws NonUniqueResultException
     * @throws OptimisticLockException|ORMException
     */
    public function testDelete(): void
    {
        // given
        $advertisementToDelete = new Advertisement();
        $advertisementToDelete->setName('Test Advertisement - delete');
        $this->entityManager->persist($advertisementToDelete);
        $this->entityManager->flush();
        $deletedAdvertisementId = $advertisementToDelete->getId();

        // when
        $this->advertisementService->delete($advertisementToDelete);

        // then
        $resultAdvertisement = $this->entityManager->createQueryBuilder()
            ->select('advertisement')
            ->from(Advertisement::class, 'advertisement')
            ->where('advertisement.id = :id')
            ->setParameter(':id', $deletedAdvertisementId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultAdvertisement);
    }

    /**
     * Test find by id.
     *
     * @throws ORMException
     */
    public function testFindById(): void
    {
        // given
        $expectedAdvertisement = new Advertisement();
        $expectedAdvertisement->setName('Test Advertisement - find by id');
        $this->entityManager->persist($expectedAdvertisement);
        $this->entityManager->flush();
        $expectedAdvertisementId = $expectedAdvertisement->getId();

        // when
//        $resultAdvertisement = $this->advertisementService->findOneById($expectedAdvertisementId);
        $resultAdvertisement = $this->advertisementService->advertisementRespository->findOneById($expectedAdvertisementId);

        // then
        $this->assertEquals($expectedAdvertisement, $resultAdvertisement);
    }
//    TODO: naprawic test przy when, bo albo nie zna funkcji w serwisie, albo duplikat wpisu dla klucza

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $advertisement = new Advertisement();
            $advertisement->setName('Test Advertisement #'.$counter);
            $this->advertisementService->save($advertisement);

            ++$counter;
        }

        // when
        $result = $this->advertisementService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // TODO: inne testy - paginacja, itd.
}
// TODO: tworzone wpisy musza miec wypelnione miejsca wymuszone na niepuste (da sie to jakos zblokowac?)