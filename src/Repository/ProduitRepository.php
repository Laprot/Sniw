<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produit::class);
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search): Query
    {
        $query= $this->findProduitASC();

        if($search->getRechercher()) {
           $query = $query
                ->andWhere("u.nom like :chaine")
                ->andWhere('u.etat = 1')
                ->orWhere('u.reference like :chaine')
                ->orWhere('u.Gencod like :chaine')
                ->orderBy('u.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }


    /**
     * @return QueryBuilder
     */

    private function findProduitASC(): QueryBuilder
    {
        //Retourne les produits dans l'odre croissant par ID , du plus ancien au plus récent
        return $this->createQueryBuilder('u')
            ->orderBy('u.id','ASC');
    }



    public function findAllAvailable() {
        return $this->createQueryBuilder('u')
            ->where('u.etat = 1')
            ->getQuery()
            ->getResult();
    }
/*
    public function byCategorie($categorie) {
        return $this->createQueryBuilder('u')

            ->andWhere('u.categorie = :categorie')
            ->andWhere('u.etat = 1')
            ->orderBy('u.id')
            ->setParameter('categorie',$categorie)
            ->getQuery()
            ->getResult();
    }

*/

    public function recherche($chaine) {
        return $this->createQueryBuilder('u')
            ->andWhere("u.nom like :chaine")
            ->andWhere('u.etat = 1')
            ->orWhere('u.reference like :chaine')
            ->orWhere('u.Gencod like :chaine')
            ->orderBy('u.id')
            ->setParameter('chaine','%'.$chaine.'%')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
