<?php

namespace App\Repository;

use App\Entity\NouvelleAdresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NouvelleAdresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method NouvelleAdresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method NouvelleAdresse[]    findAll()
 * @method NouvelleAdresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NouvelleAdresseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NouvelleAdresse::class);
    }



    // /**
    //  * @return NouvelleAdresse[] Returns an array of NouvelleAdresse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NouvelleAdresse
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
