<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Search;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Commande::class);
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
                ->orWhere('c.reference like :chaine')
                ->orWhere('c.pays like :chaine')
                ->orWhere('c.nom like :chaine')
                ->orWhere('c.prenom like :chaine')
                ->orWhere('c.email like :chaine')
                ->orWhere('c.societe like :chaine')
                ->orWhere('c.etat like :chaine')
                ->orWhere('c.date like :chaine')
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
    /**
     * @return Query
     */
    public function findAllVisibleQueryAdmin(Search $search) : Query
    {
        $query= $this->findVisibleQueryAdmin();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere('c.id like :chaine')
                ->orWhere('c.reference like :chaine')
                ->orWhere('c.pays like :chaine')
                ->orWhere('c.nom like :chaine')
                ->orWhere('c.prenom like :chaine')
                ->orWhere('c.email like :chaine')
                ->orWhere('c.societe like :chaine')
                ->orWhere('c.etat like :chaine')
                ->orWhere('c.date like :chaine')
                ->orderBy('c.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }

    private function findVisibleQueryAdmin(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom = :admin')
            ->setParameter('admin', 'admin')
            ->orderBy('c.id','DESC');
    }



    /**
     * @return Query
     */
    public function findByUser($user) {
        return $this->createQueryBuilder('u')
            ->where('u.utilisateur = :utilisateur')
            ->orderBy('u.id')
            ->setParameter('utilisateur', $user)
            ->getQuery()
            ->getResult();
    }


    public function findByIdCommande($commande) {
        return $this->createQueryBuilder('u')
            ->where('u.commande = :commande')
            ->orderBy('u.id')
            ->setParameter('commande',$commande)
            ->getQuery()
            ->getResult();
    }


    public function countAll(){
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->where('u.nom <> :nom')
            ->setParameter('nom', 'admin')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function maxPrix(){
        return $this->createQueryBuilder('u')
            ->select('MAX(u.commande)')
            ->getQuery()
            ->getResult();

    }


    public function getLastFiveCommandes($limit = 5) {
        //Permet d'afficher les 'limits' dernières commandes
        return $this->createQueryBuilder('c')
            ->groupby('c.id')
            ->orderBy('c.id','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTotalPrixCommande() {
        return $this->createQueryBuilder('u')
            ->select('MAX(u.commande)')
            ->where('u.nom <> :nom')
            ->setParameter('nom', 'admin')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCommandesUser(){
        return $this->createQueryBuilder('u')
            ->where('u.nom <> :nom')
            ->setParameter('nom', 'admin')
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Commande[] Returns an array of Commande objects
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
    public function findOneBySomeField($value): ?Commande
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
