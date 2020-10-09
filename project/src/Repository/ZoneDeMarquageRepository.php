<?php

namespace App\Repository;

use App\Entity\ZoneDeMarquage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ZoneDeMarquage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneDeMarquage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneDeMarquage[]    findAll()
 * @method ZoneDeMarquage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneDeMarquageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneDeMarquage::class);
    }

    // /**
    //  * @return ZoneDeMarquage[] Returns an array of ZoneDeMarquage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZoneDeMarquage
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
