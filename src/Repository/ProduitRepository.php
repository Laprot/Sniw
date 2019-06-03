<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Filtre;
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

    public function findArray($array) {
        return $this->createQueryBuilder('u')
            ->where('u.id IN (:array)')
            ->setParameter('array', $array)
            ->andWhere('u.etat = 1')
            ->getQuery()
            ->getResult();
    }



    /*
        public function byCategorie($categorie)
        {
            $qb = $this->createQueryBuilder('u')
                ->select('u')
                ->where('u.id_categorie = :id_categorie')
                ->andWhere('u.etat = 1')
                ->orderBy('u.id')
                ->setParameter('id_categorie', $categorie);
            return $qb->getQuery()->getResult();
        }
    */


    public function byCategorie($categorie)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.categories', 'C')
            ->where(' C.id = :categorie_id ')
            ->andWhere('p.etat = 1')
            ->orderBy('p.id')
            ->setParameter('categorie_id', $categorie);
        return $qb->getQuery()->getResult();
    }
    public function countProduits()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.etat=1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countProduitsCategorie() {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->leftJoin('p.categories','C')
            ->where('C.id = :categorie_id')
            ->andWhere('p.etat = 1')
            ->getQuery()
            ->getScalarResult();
    }


    /*
    public function findByIdCommande($commande) {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.commande = :commande')
            ->orderBy('u.id')
            ->setParameter('commande',$commande)
            ->getQuery()
            ->getResult();
    }
*/
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
     * @return Query
     */
    public function findAllVisibleQueryAdmin(Search $search): Query
    {
        $query= $this->findProduitASC();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere("u.nom like :chaine")
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

    public function findAllAvailableCatalogue() {
        return $this->createQueryBuilder('u')
            ->where('u.etat = 1');
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



    //Filtre produit belle france ou produit bio
    /**
     * @return Query
     */
    public function findProduitCheckbox(Filtre $filtre, Produit $produit): Query
    {
        $query= $this->findAllAvailableCatalogue();

        if($filtre->getIsBelleFrance()) {
            $query = $query
                ->andWhere("u.produit_belle_france like :filtre")
                ->setParameter('filtre', $filtre->getIsBelleFrance());
        }
        if($filtre->getIsBio()) {
            $query = $query
                ->andWhere("u.produit_bio like :filtre")
                ->setParameter('filtre', $filtre->getIsBio());
        }


        return $query->getQuery();
    }




}
