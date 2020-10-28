<?php

namespace App\Repository;

use App\Entity\PriceImpression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceImpression|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceImpression|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceImpression[]    findAll()
 * @method PriceImpression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceImpressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceImpression::class);
    }

    // /**
    //  * @return PriceImpression[] Returns an array of PriceImpression objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceImpression
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
