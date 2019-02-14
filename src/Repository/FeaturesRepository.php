<?php

namespace App\Repository;

use App\Entity\Features;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Features|null find($id, $lockMode = null, $lockVersion = null)
 * @method Features|null findOneBy(array $criteria, array $orderBy = null)
 * @method Features[]    findAll()
 * @method Features[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeaturesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Features::class);
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search): Query
    {
        $query= $this->findFeatures();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere("u.nom like :chaine")
                ->orWhere('u.id like :chaine')
                ->orderBy('u.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }


    /**
     * @return QueryBuilder
     */

    private function findFeatures(): QueryBuilder
    {
        //Retourne les produits dans l'odre croissant par ID , du plus ancien au plus récent
        return $this->createQueryBuilder('u');
    }




    // /**
    //  * @return Features[] Returns an array of Features objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Features
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
