<?php

/**
 * Advertisement repository.
 */

namespace App\Repository;

use App\Entity\Advertisement;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AdvertisementRepository.
 *
 * @extends ServiceEntityRepository<Advertisement>
 *
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Advertisement::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial advertisement.{id, name, description, price, location, date, is_active}',
                'partial advertiser.{id, email, phone, name}',
                'partial category.{id, name}'
            )
            ->join('advertisement.advertiser', 'advertiser')
            ->join('advertisement.category', 'category')
            ->orderBy('advertisement.date', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function save(Advertisement $advertisement): void
    {
        if (null == $advertisement->getId()) {
            $advertisement->setIsActive(false);
        }

        $this->_em->persist($advertisement);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Advertisement $advertisement Advertisement entity
     */
    public function delete(Advertisement $advertisement): void
    {
        $this->_em->remove($advertisement);
        $this->_em->flush();
    }

    /**
     * Count advertisements by category.
     *
     * @param Category $category Category
     *
     * @return int Number of advertisements in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('advertisement.id'))
            ->where('advertisement.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get advertisements by category.
     *
     * @param Category $category Category
     *
     * @return QueryBuilder QueryBuilder
     */
    public function getByCategory(Category $category): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial advertisement.{id, name, description, price, location, date, is_active}',
                'partial advertiser.{id, email, phone, name}',
            )
            ->join('advertisement.advertiser', 'advertiser')
            ->where('advertisement.category = :category')
            ->andWhere('advertisement.is_active = :is_active')
            ->setParameter(':category', $category)
            ->setParameter(':is_active', 1)
            ->orderBy('advertisement.date', 'DESC');
    }

    /**
     * Get advertisements by inactive status.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function getByUnacceptedEntity(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial advertisement.{id, name, description, price, location, date, is_active}',
                'partial advertiser.{id, email, phone, name}',
                'partial category.{id, name}'
            )
            ->join('advertisement.advertiser', 'advertiser')
            ->join('advertisement.category', 'category')
            ->where('advertisement.is_active = :is_active')
            ->setParameter(':is_active', 0)
            ->orderBy('advertisement.date', 'DESC');
    }

    /**
     * Get advertisements by inactive status.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function getByAcceptedEntity(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial advertisement.{id, name, description, price, location, date, is_active}',
                'partial advertiser.{id, email, phone, name}',
                'partial category.{id, name}'
            )
            ->join('advertisement.advertiser', 'advertiser')
            ->join('advertisement.category', 'category')
            ->where('advertisement.is_active = :is_active')
            ->setParameter(':is_active', 1)
            ->orderBy('advertisement.date', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('advertisement');
    }

    //    /**
    //     * Save record.
    //     *
    //     * @param Advertisement $entity Advertisement entity
    //     */
    //    public function save(Advertisement $entity, bool $flush = false): void
    //    {
    //        $this->getEntityManager()->persist($entity);
    //
    //        if ($flush) {
    //            $this->getEntityManager()->flush();
    //        }
    //    }
    //
    //    /**
    //     * Remove record.
    //     *
    //     * @param Advertisement $entity Advertisement entity
    //     */
    //    public function remove(Advertisement $entity, bool $flush = false): void
    //    {
    //        $this->getEntityManager()->remove($entity);
    //
    //        if ($flush) {
    //            $this->getEntityManager()->flush();
    //        }
    //    }

    //    /**
    //     * @return Advertisement[] Returns an array of Advertisement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Advertisement
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
