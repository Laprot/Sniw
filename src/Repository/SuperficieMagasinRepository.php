<?php

namespace App\Repository;

use App\Entity\SuperficieMagasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SuperficieMagasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperficieMagasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperficieMagasin[]    findAll()
 * @method SuperficieMagasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperficieMagasinRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SuperficieMagasin::class);
    }

    // /**
    //  * @return SuperficieMagasin[] Returns an array of SuperficieMagasin objects
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
    public function findOneBySomeField($value): ?SuperficieMagasin
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
