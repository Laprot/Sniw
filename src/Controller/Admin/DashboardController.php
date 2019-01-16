<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
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


        $qb = $this->repository->createQueryBuilder('entity');
        $qb->select('COUNT(entity) ' );
        $count = $qb->getQuery()->getSingleScalarResult();

        return $this->render('admin/index.html.twig', [
            'users' => $users ,
            'count' => $count
        ]);
    }

}