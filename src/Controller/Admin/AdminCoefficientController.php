<?php


namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Produit;
use App\Entity\Coefficient;
use App\Entity\Search;
use App\Entity\User;
use App\Form\GroupeType;
use App\Form\CoefficientType;
use App\Form\SearchType;
use App\Form\UserType;
use App\Repository\GroupeRepository;
use App\Repository\CoefficientRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCoefficientController extends AbstractController
{

    /**
     * @var GroupeRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(CoefficientRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }



    /**
     * @Route("/admin/coefficient/show", name="coefficient_show")
     */
    public function showGroupe( Request $request)
    {
        // Récupère tous les groupes
        $coefficients = $this->repository->findAll();


        return $this->render('admin/groupe/coefficient_show.html.twig', [
            'coefficients' => $coefficients
        ]);
    }



    /**
     * @Route("/admin/coefficient/{id}/edit", name="coefficient_edit", methods="GET|POST")
     */
    public function edit(Request $request, Coefficient $coefficient, PaginatorInterface $paginator)
    {
        $form = $this->createForm(CoefficientType::class, $coefficient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('coefficient_show', [
                'id' => $coefficient->getId()
            ]);
        }

        return $this->render('admin/groupe/coefficient_edit.html.twig', [
            'coefficient' => $coefficient,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/coefficient/new", name="coefficient_new")
     */
    public function newCoeff(Request $request)
    {
        // 1) build the form
        $coefficient = new Coefficient();
        $form = $this->createForm(CoefficientType::class, $coefficient);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the coeff
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coefficient);
            $entityManager->flush();


            return $this->redirectToRoute('coefficient_show');
        }

        return $this->render(
            'admin/groupe/coefficient_new.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/coefficient/{id}/delete", name="coefficient_delete", methods="DELETE")
     */
    public function delete(Request $request, Coefficient $coefficient)
    {
        if ($this->isCsrfTokenValid('delete' . $coefficient->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coefficient);
            $em->flush();
        }

        return $this->redirectToRoute('coefficient_show');
    }
}
