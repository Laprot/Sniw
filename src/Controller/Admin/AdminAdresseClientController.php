<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdresseUserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAdresseClientController extends AbstractController
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
     * @Route("/admin/client/adresses/show", name="client_adresse_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        // Récupère toutes les adresses des utilisateurs
        //$users = $this->repository->findAll();

        //Pagination avec 10 users par page
        $users = $paginator->paginate(
            $this->repository->findAllVisibleQuery(),
            $request->query->getInt('page', 1), 10
        );


        //Récupérer le nombre d'utilisateurs
        $qb = $this->repository->createQueryBuilder('entity');
        $qb->select('COUNT(entity)');
        $count = $qb->getQuery()->getSingleScalarResult();


        return $this->render('admin/client/adresses.html.twig', [
            'users' => $users,
            'count' => $count
        ]);
    }

    /**
     * @Route("/admin/{id}/client/adresses/edit", name="client_edit_adresse", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(AdresseUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_adresse_show', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('admin/client/adresses_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}/client/adresses/delete", name="client_adresse_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('client_adresse_show');
    }

}