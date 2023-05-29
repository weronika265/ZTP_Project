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
//            TODO: cos jest nie tak
//            ->select('advertiser', 'partial advertisement.{id}')
//            ->join('advertiser.advertisement', 'advertisement')
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
        return $queryBuilder ?? $this->createQueryBuilder('advertisement');
    }

    /**
     * Save record.
     *
     * @param Advertiser $entity Advertiser entity
     */
    public function save(Advertiser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove record.
     *
     * @param Advertiser $entity Advertiser entity
     */
    public function remove(Advertiser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Advertiser[] Returns an array of Advertiser objects
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

//    public function findOneBySomeField($value): ?Advertiser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
