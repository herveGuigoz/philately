<?php

namespace App\Repository;

use App\Entity\StampFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StampFamily|null find($id, $lockMode = null, $lockVersion = null)
 * @method StampFamily|null findOneBy(array $criteria, array $orderBy = null)
 * @method StampFamily[]    findAll()
 * @method StampFamily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampFamilyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StampFamily::class);
    }

    // /**
    //  * @return StampFamily[] Returns an array of StampFamily objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StampFamily
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
