<?php


namespace App\Controller\Admin;

use App\Entity\Features;
use App\Entity\Search;
use App\Form\FeaturesType;
use App\Form\SearchType;
use App\Repository\FeaturesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminFeaturesController extends AbstractController
{

    /**
     * @var FeaturesRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(FeaturesRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/features/show", name="features_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {

        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        //Pagination avec 10 groupes par page
        $features = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('admin/produits/features/features.html.twig', [
            'features' => $features,
            'count' => $features->getTotalItemCount(),
            'form' =>$form->createView()
        ]);
    }


    /**
     * @Route("/admin/feature/{id}/edit", name="features_edit", methods="GET|POST")
     */
    public function edit(Request $request, Features $feature)
    {
        $form = $this->createForm(FeaturesType::class, $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $metadata = $this->em->getClassMetaData(get_class($feature));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('features_show', [
                'id' => $feature->getId()
            ]);
        }

        return $this->render('admin/produits/features/features_edit.html.twig', [
            'feature' => $feature,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/feature/new", name="features_new")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $feature = new Features();
        $form = $this->createForm(FeaturesType::class, $feature);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $metadata = $this->em->getClassMetaData(get_class($feature));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            // 4) save the manufacturer
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feature);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('features_show');
        }

        return $this->render(
            'admin/produits/features/features_new.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/feature/{id}/delete", name="features_delete", methods="DELETE")
     */
    public function delete(Request $request, Features $feature)
    {
        if ($this->isCsrfTokenValid('delete' . $feature->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($feature);
            $em->flush();
        }

        return $this->redirectToRoute('features_show');
    }


}
