<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\CommandeRepository;
use App\Repository\CommandeTypeProduitsRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DashboardController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UserRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index()
    {
        // Récupère tous les utilisateurs
        //$users = $this->repository->findAll();


        //Récupère les 5 dernières inscriptions
        $users = $this->repository->getLastFiveUsers();

        $commandes = $this->getDoctrine()->getRepository(Commande::class)->getLastFiveCommandes();


        $qb = $this->repository->createQueryBuilder('entity');
        $qb->select('COUNT(entity) ');
        $countclients = $qb->getQuery()->getSingleScalarResult();


        $countcommandes = $this->getDoctrine()->getRepository(Commande::class)->countAll();


        //Récupère les commandes user
        $commandesUser = $this->getDoctrine()->getRepository(Commande::class)->getCommandesUser();

        //Instancie un tableau contenu les commandes user
        $tableau[] = $commandesUser;
        $somme =0;


        $max = -PHP_INT_MAX ;

        foreach($tableau as $cle=>$value) {
            if (is_array($value) || is_object($value))
                foreach($value as $commande) {
                    $prixHT = $commande->getCommande()['prixHT'];
                    //On additionne le prix
                    $somme += $prixHT;

                    //Récupère le prix max d'une commande
                    if ($prixHT > $max)
                    {
                        $max = $prixHT;
                    }

                    //Essayer de récupérer le best seller

                }
        }


        return $this->render('admin/index.html.twig', [
            'users' => $users ,
            'countClients' => $countclients,
            'countCommandes' => $countcommandes,
            'commandes'=> $commandes,
            'somme' => $somme,
            'maxPrix' => $max
        ]);
    }

}