<?php

namespace App\Repository;

use App\Entity\Logistique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Logistique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logistique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logistique[]    findAll()
 * @method Logistique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogistiqueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Logistique::class);
    }

    // /**
    //  * @return Logistique[] Returns an array of Logistique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Logistique
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
