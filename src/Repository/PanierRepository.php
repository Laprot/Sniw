<?php

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC');
    }


    /**
     * @return Query
     */
    public function findAllVisibleQuery(Search $search) : Query
    {
        $query = $this->findVisibleQuery();

        if ($search->getRechercher()) {
            $query = $query
                ->andWhere('c.id like :chaine')
                ->orWhere('c.reference like :chaine')
                ->orWhere('c.nom like :chaine')
                ->orWhere('c.prenom like :chaine')
                ->orWhere('c.date like :chaine')
                ->orderBy('c.id')
                ->setParameter('chaine', '%' . $search->getRechercher() . '%');
        }
        return $query->getQuery();

    }
}
