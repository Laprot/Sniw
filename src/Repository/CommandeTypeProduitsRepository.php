<?php

namespace App\Repository;

use App\Entity\CommandeTypeProduits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandeTypeProduits|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeTypeProduits|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeTypeProduits[]    findAll()
 * @method CommandeTypeProduits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeTypeProduitsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandeTypeProduits::class);
    }

    // /**
    //  * @return CommandeTypeProduits[] Returns an array of CommandeTypeProduits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandeTypeProduits
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
