<?php

namespace App\Repository;

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
    public function findAllVisibleQuery(): Query
    {
        return $this->findUserDESC()->getQuery();
    }
    /**
     * @return QueryBuilder
     */

    private function findUserDESC(): QueryBuilder
    {
        //Retourne les utilisateurs dans l'odre décroissant par ID , du plus récent au plus ancien
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


    public function recherche($chaine)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom like :chaine')
            ->orWhere('u.prenom like :chaine')
            ->orWhere('u.email like :chaine')
            ->orWhere('u.societe like :chaine')
            ->orderBy('u.id')
            ->setParameter('chaine', '%' . $chaine . '%')
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
