<?php

/**
 * Advertiser repository.
 */

namespace App\Repository;

use App\Entity\Advertiser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AdvertiserRepository.
 *
 * @extends ServiceEntityRepository<Advertiser>
 *
 * @method Advertiser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertiser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertiser[]    findAll()
 * @method Advertiser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertiserRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertiser::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial advertiser.{id, email, phone, name}')
            ->orderBy('advertiser.email', 'DESC');
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('advertiser');
    }

    /**
     * Save entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function save(Advertiser $advertiser): void
    {
        $this->_em->persist($advertiser);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Advertiser $advertiser Advertiser entity
     */
    public function delete(Advertiser $advertiser): void
    {
        $this->_em->remove($advertiser);
        $this->_em->flush();
    }
}
