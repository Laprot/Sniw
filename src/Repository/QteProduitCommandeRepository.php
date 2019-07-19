<?php

namespace App\Repository;

use App\Entity\QteProduitCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QteProduitCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method QteProduitCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method QteProduitCommande[]    findAll()
 * @method QteProduitCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QteProduitCommandeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QteProduitCommande::class);
    }

    // /**
    //  * @return QteProduitCommande[] Returns an array of QteProduitCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QteProduitCommande
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
