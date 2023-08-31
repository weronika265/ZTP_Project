<?php

/**
 * Advertisement service tests.
 */

namespace App\Tests\Service;

use App\Entity\Advertisement;
use App\Entity\Advertiser;
use App\Entity\Category;
use App\Repository\AdvertisementRepository;
use App\Repository\CategoryRepository;
use App\Service\AdvertisementService;
use App\Service\AdvertisementServiceInterface;
use App\Service\AdvertiserService;
use App\Service\CategoryService;
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
        $this->advertisementRepository = $container->get(advertisementRepository::class);
        $this->categoryService = $container->get(CategoryService::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->advertiserService = $container->get(AdvertiserService::class);
    }

    /**
     * Test save.
     *
     * @throws NoResultException|NonUniqueResultException
     */
    public function testSave(): void
    {
        // given
        $expectedCategory = $this->createCategory('Test category - save for advertisement');
        $expectedAdvertiser = $this->createAdvertiser();

        $expectedAdvertisement = new Advertisement();
        $expectedAdvertisement->setName('Test advertisement - save');
        $expectedAdvertisement->setDescription('Description of test advertisement to save');
        $expectedAdvertisement->setLocation('Test location');
        $expectedAdvertisement->setDate(new \DateTimeImmutable('now'));
        $expectedAdvertisement->setIsActive(0);

        $expectedAdvertisement->setAdvertiser($expectedAdvertiser);
        $expectedAdvertisement->setCategory($expectedCategory);

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
        $expectedCategory = $this->createCategory('Test category - delete for advertisement');
        $expectedAdvertiser = $this->createAdvertiser();

        $advertisementToDelete = new Advertisement();
        $advertisementToDelete->setName('Test advertisement - delete');
        $advertisementToDelete->setDescription('Description of test advertisement to delete');
        $advertisementToDelete->setLocation('Test location');
        $advertisementToDelete->setDate(new \DateTimeImmutable('now'));
        $advertisementToDelete->setIsActive(0);

        $advertisementToDelete->setAdvertiser($expectedAdvertiser);
        $advertisementToDelete->setCategory($expectedCategory);

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
        $expectedCategory = $this->createCategory('Test category for advertisement');
        $expectedAdvertiser = $this->createAdvertiser();

        $expectedAdvertisement = new Advertisement();
        $expectedAdvertisement->setName('Test Advertisement - find by id');
        $expectedAdvertisement->setDescription('Description of test advertisement');
        $expectedAdvertisement->setLocation('Test location');
        $expectedAdvertisement->setDate(new \DateTimeImmutable('now'));
        $expectedAdvertisement->setIsActive(0);

        $expectedAdvertisement->setAdvertiser($expectedAdvertiser);
        $expectedAdvertisement->setCategory($expectedCategory);

        $this->entityManager->persist($expectedAdvertisement);
        $this->entityManager->flush();
        $expectedAdvertisementId = $expectedAdvertisement->getId();

        // when
        $resultAdvertisement = $this->advertisementRepository->findOneById($expectedAdvertisementId);

        // then
        $this->assertEquals($expectedAdvertisement, $resultAdvertisement);
    }

    /*
     * Test pagination list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        $category = $this->createCategory('Test category for advertisement pagination');
        $advertiser = $this->createAdvertiser();

        while ($counter < $dataSetSize) {
            $advertisement = new Advertisement();
            $advertisement->setName('Test Advertisement #'.$counter);
            $advertisement->setDescription('Description of test advertisement');
            $advertisement->setLocation('Test location');
            $advertisement->setDate(new \DateTimeImmutable('now'));
            $advertisement->setIsActive(1);

            $advertisement->setAdvertiser($advertiser);
            $advertisement->setCategory($category);

            $this->advertisementService->save($advertisement);

            ++$counter;
        }

        // when
        $result = $this->advertisementService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /*
     * Test pagination list by unaccepted entity.
     */
    public function testGetPaginatedListWithUnacceptedEntity(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $testCategoryName = 'Test pagination by unaccepted entity';

        $counter = 0;
        $category = $this->createCategory($testCategoryName);
        $advertiser = $this->createAdvertiser();

        while ($counter < $dataSetSize) {
            $advertisement = new Advertisement();
            $advertisement->setName('Test Advertisement #'.$counter);
            $advertisement->setDescription('Description of test advertisement');
            $advertisement->setLocation('Test location');
            $advertisement->setDate(new \DateTimeImmutable('now'));
            $advertisement->setIsActive(0);

            $advertisement->setAdvertiser($advertiser);
            $advertisement->setCategory($category);

            $this->advertisementService->save($advertisement);

            ++$counter;
        }

        // when
        $result = $this->advertisementService->getPaginatedListWithUnacceptedEntity($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // co nie tak z tym is active -> 1 (chociaz jest na true). Czy nie o to chodzi?
    /*
     * Test pagination list by accepted entity.
     */
    /*    public function testGetPaginatedListWithAcceptedEntity(): void
        {
            // given
            $page = 1;
            $dataSetSize = 10;
            $expectedResultSize = 10;

            $testCategoryName = 'Test pagination by accepted entity';

            $counter = 0;
            $category = $this->createCategory($testCategoryName);
            $advertiser = $this->createAdvertiser();

            while ($counter < $dataSetSize) {
                $advertisement = new Advertisement();
                $advertisement->setName('Test Advertisement #'.$counter);
                $advertisement->setDescription('Description of test advertisement');
                $advertisement->setLocation('Test location');
                $advertisement->setDate(new \DateTimeImmutable('now'));
                $advertisement->setIsActive(1);

                $advertisement->setAdvertiser($advertiser);
                $advertisement->setCategory($category);

                $this->advertisementService->save($advertisement);

                ++$counter;
            }

            // when
            $result = $this->advertisementService->getPaginatedListWithAcceptedEntity($page);

            // then
            $this->assertEquals($expectedResultSize, $result->count());
        }*/

    // tu tez
    /*
     * Test pagination list by category.
     */
    /*    public function testGetPaginatedListByCategory(): void
        {
            // given
            $page = 1;
            $dataSetSize = 10;
            $expectedResultSize = 10;

            $testCategoryName = 'Test advertisement pagination by category';

            $counter = 0;
            $category = $this->createCategory($testCategoryName);
            $advertiser = $this->createAdvertiser();

            while ($counter < $dataSetSize) {
                $advertisement = new Advertisement();
                $advertisement->setName('Test Advertisement #'.$counter);
                $advertisement->setDescription('Description of test advertisement');
                $advertisement->setLocation('Test location');
                $advertisement->setDate(new \DateTimeImmutable('now'));
                $advertisement->setIsActive(1);

                $advertisement->setAdvertiser($advertiser);
                $advertisement->setCategory($category);

                $this->advertisementService->save($advertisement);

                ++$counter;
            }

            // when
            $result = $this->advertisementService->getPaginatedListByCategory($page, $category);

            // then
            $this->assertEquals($expectedResultSize, $result->count());
        }*/

    /**
     * Create advertiser.
     */
    public function createAdvertiser(): Advertiser
    {
        $advertiser = new Advertiser();
        $advertiser->setEmail('test@mail.com');
        $this->advertiserService->save($advertiser);

        return $advertiser;
    }

    /**
     * Create category.
     */
    public function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $this->categoryService->save($category);

        return $category;
    }
}
