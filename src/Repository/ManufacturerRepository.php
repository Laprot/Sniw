<?php

namespace App\Repository;

use App\Entity\Manufacturer;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Manufacturer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manufacturer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manufacturer[]    findAll()
 * @method Manufacturer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufacturerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Manufacturer::class);
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search): Query
    {
        $query= $this->findManufacturerASC();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere("u.id like :chaine")
                ->orWhere('u.nom like :chaine')
                ->orderBy('u.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }
    /**
     * @return QueryBuilder
     */

    private function findManufacturerASC(): QueryBuilder
    {
        //Retourne les utilisateurs dans l'odre décroissant par ID , du plus récent au plus ancien
        return $this->createQueryBuilder('u')
            ->orderBy('u.nom','ASC');
    }


    // /**
    //  * @return Manufacturer[] Returns an array of Manufacturer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Manufacturer
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
