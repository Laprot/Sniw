<?php

namespace App\Controller\Admin;

use App\Entity\Search;
use App\Entity\SuperficieMagasin;
use App\Entity\User;
use App\Form\AdresseUserType;
use App\Form\RechercheAdresseType;
use App\Form\SearchType;
use App\Form\SuperficieMagasinType;
use App\Repository\SuperficieMagasinRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminSuperficieController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(SuperficieMagasinRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/client/superficie/show", name="client_superficie_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        $superficies = $this->repository->findAll();

        return $this->render('admin/client/superficie.html.twig', [
            'superficies'=>$superficies
        ]);
    }

    /**
     * @Route("/admin/{id}/client/superficie/edit", name="client_superficie_edit", methods="GET|POST")
     */
    public function edit(Request $request, SuperficieMagasin $superficieMagasin)
    {
        $form = $this->createForm(SuperficieMagasinType::class, $superficieMagasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_superficie_show', [
                'id' => $superficieMagasin->getId()
            ]);
        }
        return $this->render('admin/client/superficie_edit.html.twig', [
            'superficie' => $superficieMagasin,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/client/superficie/new", name="client_superficie_new")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $superficie = new SuperficieMagasin();
        $form = $this->createForm(SuperficieMagasinType::class, $superficie);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)

            // 4) save the group
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($superficie);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('client_superficie_show');
        }

        return $this->render(
            'admin/client/superficie_new.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }



    /**
     * @Route("/admin/{id}/client/superficie/delete", name="client_superficie_delete", methods="DELETE")
     */
    public function delete(Request $request, SuperficieMagasin $superficieMagasin)
    {
        if ($this->isCsrfTokenValid('delete'.$superficieMagasin->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($superficieMagasin);
            $em->flush();
        }

        return $this->redirectToRoute('client_superficie_show');
    }
}
