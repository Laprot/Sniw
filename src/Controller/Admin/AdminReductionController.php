<?php


namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Produit;
use App\Entity\Reduction;
use App\Entity\Search;
use App\Entity\User;
use App\Form\GroupeType;
use App\Form\ReductionType;
use App\Form\SearchType;
use App\Form\UserType;
use App\Repository\GroupeRepository;
use App\Repository\ReductionRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminReductionController extends AbstractController
{

    /**
     * @var GroupeRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ReductionRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/reduction/show", name="reduction_show")
     */
    public function show( Request $request)
    {
        // Récupère tous les groupes
         $reductions = $this->repository->findAll();


        return $this->render('admin/groupe/reduction_show.html.twig', [
            'reductions' => $reductions
        ]);
    }




    /**
     * @Route("/admin/reduction/{id}/edit", name="reduction_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reduction $reduction,PaginatorInterface $paginator)
    {
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reduction_show', [
                'id' => $reduction->getId()
            ]);
        }

        return $this->render('admin/groupe/reduction_edit.html.twig', [
            'reduction' => $reduction,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/reduction/new", name="reduction_new")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $reduction = new Reduction();
        $form = $this->createForm(ReductionType::class, $reduction);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Catégories
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(['id'=>$reduction->getCategories()]);

            //Produits par catégories
            $produits = $this->getDoctrine()->getRepository(Produit::class)->byCategorie($categorie);

           foreach($produits as $p) {
               $prixUnite = $p->getPrixUnite();


               $reduc = $reduction->getNewReduction() * $prixUnite;
               $prixFinalUnite = $prixUnite - $reduc;

               $p->setPrixUnite($prixFinalUnite);
               $prixFinal = $prixFinalUnite * $p->getUniteParCarton();

           }



            // 4) save the group
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reduction);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('reduction_show');
        }

        return $this->render(
            'admin/groupe/reduction_new.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/reduction/{id}/delete", name="reduction_delete", methods="DELETE")
     */
    public function delete(Request $request, Reduction $reduction)
    {
        if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reduction);
            $em->flush();
        }

        return $this->redirectToRoute('reduction_show');
    }
}
