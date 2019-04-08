<?php

namespace App\Repository;

use App\Entity\CommandeTypeProduits;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
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


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search) : Query
    {
        $query= $this->findVisibleQuery();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere('c.id like :chaine')
                ->orWhere('c.nom like :chaine')
                ->innerJoin('c.commande', 'commande')
                ->addSelect('commande')
                ->orderBy('c.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }


    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC');
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
