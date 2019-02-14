<?php


namespace App\Controller\Admin;

use App\Entity\Categorie;

use App\Entity\Search;
use App\Form\CategorieType;


use App\Form\SearchType;
use App\Repository\CategorieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategorieController extends AbstractController
{

    /**
     * @var CategorieRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(CategorieRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/categorie/show", name="categorie_show")
     */
    public function show(PaginatorInterface $paginator,Request $request)
    {




        //$categories_parent = $this->repository->find('id_parent');


        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);


        $categories = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );


        return $this->render('admin/categorie/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'count' => $categories->getTotalItemCount()
           // 'categories_parent' => $categories_parent
        ]);
    }


    /**
     * @Route("/admin/categorie/new", name="categorie_new")
     */
    public function register(Request $request)
    {

        $categories = $this->repository->findAll();
        // 1) build the form
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $metadata = $this->em->getClassMetaData(get_class($categorie));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());


            // 4) save the manufacturer
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('categorie_show');
        }

        return $this->render(
            'admin/categorie/categorie_new.html.twig', [
                'form' => $form->createView(),
                'categories'=>$categories
            ]
        );
    }


    /**
     * @Route("/admin/categorie/sous_cat/{id}", name="sous_categorie")
     */
    public function sous_categorie(PaginatorInterface $paginator,Request $request, Categorie $categorie) {

        //$categories_parent = $this->repository->find('id_parent');


        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);


        $categories = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );


        return $this->render('admin/categorie/sous_categorie.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'categorie' =>$categorie,
            'count' => $categories->getTotalItemCount()
            // 'categories_parent' => $categories_parent
        ]);



    }


    /**
     * @Route("/admin/categorie/{id}/edit", name="categorie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Categorie $categorie)
    {

        $categories = $this->repository->findAll();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $metadata = $this->em->getClassMetaData(get_class($categorie));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fabricant_show', [
                'id' => $categorie->getId()
            ]);
        }

        return $this->render('admin/categorie/categorie_edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'categories' =>$categories
        ]);
    }

    /**
     * @Route("/admin/categorie/{id}/delete", name="categorie_delete", methods="DELETE")
     */
    public function delete(Request $request, Categorie $categorie)
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_show');
    }


    private function categoryTree($id_parent = null)
    {
        $a=array();
        $rows = $this->repository->findBy(array('id_parent' => $id_parent), array('id' => 'ASC'));

        foreach ($rows as $row) {
            array_push(
                $a,
                array_filter([
                    $row->getId() => $row->getNom(),
                    'children' => $this->categoryTree($row->getId())
                ])
            );
        }
        return $a;
    }

}
