<?php

/**
 * Advertiser service tests.
 */

namespace App\Tests\Service;

use App\Entity\Advertiser;
use App\Repository\AdvertiserRepository;
use App\Service\AdvertiserService;
use App\Service\AdvertiserServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AdvertiserServiceTest.
 */
class AdvertiserServiceTest extends KernelTestCase
{
    /**
     * Advertiser service.
     */
    private ?AdvertiserServiceInterface $advertiserService;

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
        $this->advertiserRepository = $container->get(AdvertiserRepository::class);
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
        $expectedAdvertiser = new Advertiser();
        $expectedAdvertiser->setEmail('saveAdvertiserTest@mail.com');

        // when
        $this->advertiserService->save($expectedAdvertiser);

        // then
        $expectedAdvertiserId = $expectedAdvertiser->getId();
        $resultAdvertiser = $this->entityManager->createQueryBuilder()
            ->select('advertiser')
            ->from(Advertiser::class, 'advertiser')
            ->where('advertiser.id = :id')
            ->setParameter(':id', $expectedAdvertiserId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedAdvertiser, $resultAdvertiser);
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
        $advertiserToDelete = new Advertiser();
        $advertiserToDelete->setEmail('deleteAdvertiserTest@mail.com');

        $this->entityManager->persist($advertiserToDelete);
        $this->entityManager->flush();
        $deletedAdvertiserId = $advertiserToDelete->getId();

        // when
        $this->advertiserService->delete($advertiserToDelete);

        // then
        $resultAdvertiser = $this->entityManager->createQueryBuilder()
            ->select('advertiser')
            ->from(Advertiser::class, 'advertiser')
            ->where('advertiser.id = :id')
            ->setParameter(':id', $deletedAdvertiserId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultAdvertiser);
    }

    /**
     * Test find by id.
     *
     * @throws ORMException
     */
    public function testFindById(): void
    {
        // given
        $expectedAdvertiser = new Advertiser();
        $expectedAdvertiser->setEmail('advertiserToFind@mail.com');

        $this->entityManager->persist($expectedAdvertiser);
        $this->entityManager->flush();
        $expectedAdvertiserId = $expectedAdvertiser->getId();

        // when
        $resultAdvertiser = $this->advertiserRepository->findOneById($expectedAdvertiserId);

        // then
        $this->assertEquals($expectedAdvertiser, $resultAdvertiser);
    }

    /**
     * Test if advertiser email exists.
     */
    public function testAdvertiserEmailExists(): void
    {
        // given
        $testEmail = 'advertiserExistsTest@mail.com';
        $expectedAdvertiser = new Advertiser();
        $expectedAdvertiser->setEmail($testEmail);

        $this->entityManager->persist($expectedAdvertiser);
        $this->entityManager->flush();

        // when
        $expectedAdvertiserId = $expectedAdvertiser->getId();

        // then
        $resultAdvertiser = $this->entityManager->createQueryBuilder()
            ->select('advertiser')
            ->from(Advertiser::class, 'advertiser')
            ->where('advertiser.id = :id')
            ->setParameter(':id', $expectedAdvertiserId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($testEmail, $resultAdvertiser->getEmail());
    }
}
