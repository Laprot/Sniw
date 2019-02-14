<?php

namespace App\Repository;

use App\Entity\Search;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search) : Query
    {
        $query= $this->findVisibleQuery();


        if($search->getRechercher()) {
            $query = $query
                ->andWhere('u.nom like :chaine')
                ->orWhere('u.prenom like :chaine')
                ->orWhere('u.email like :chaine')
                ->orWhere('u.id like :chaine')
                ->orWhere('u.societe like :chaine')
                ->orderBy('u.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }

    /**
     * @return Query
     */
    public function findAllVisibleQueryAdresse(Search $search) : Query
    {
        $query= $this->findVisibleQuery();

        if($search->getRechercher()) {
            $query = $query
                ->andWhere('u.id like :chaine')
                ->orWhere('u.email like :chaine')
                ->orWhere('u.prenom like :chaine')
                ->orWhere('u.nom like :chaine')
                ->orWhere('u.code_postal like :chaine')
                ->orWhere('u.adresse like :chaine')
                ->orWhere('u.ville like :chaine')
                ->orWhere('u.pays like :chaine')
                ->orderBy('u.id')
                ->setParameter('chaine','%'.$search->getRechercher().'%');
        }
        return $query->getQuery();
    }



    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id','DESC');
    }

    public function getLastFiveUsers($limit = 5) {
        //Permet d'afficher les 'limits' dernières inscriptions
        return $this->createQueryBuilder('u')
            ->groupby('u.id')
            ->orderBy('u.id','DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    public function recherche($search)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom like :chaine')
            ->orWhere('u.prenom like :chaine')
            ->orWhere('u.email like :chaine')
            ->orWhere('u.societe like :chaine')
            ->orderBy('u.id')
            ->setParameter('chaine', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }


    public function rechercheAdresse($chaine) {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom like :chaine')
            ->orWhere('u.prenom like :chaine')
            ->orWhere('u.email like :chaine')
            ->orWhere('u.adresse like :chaine')
            ->orWhere('u.code_postal like :chaine')
            ->orWhere('u.ville like :chaine')
            ->orWhere('u.pays like :chaine')
            ->orderBy('u.id')
            ->setParameter('chaine','%'.$chaine.'%')
            ->getQuery()
            ->getResult();
    }

    public function countRecherche(){
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }







    // /**
    //  * @return Test[] Returns an array of Test objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Test
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
