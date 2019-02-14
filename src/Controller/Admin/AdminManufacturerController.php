<?php


namespace App\Controller\Admin;

use App\Entity\Manufacturer;
use App\Entity\Search;
use App\Form\ManufacturerType;
use App\Form\SearchType;
use App\Repository\ManufacturerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminManufacturerController extends AbstractController
{

    /**
     * @var ManufacturerRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ManufacturerRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/fabricant/show", name="fabricant_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {


        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        //Pagination avec 20 fabricants par page
        $fabricants = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 20
        );

        return $this->render('admin/fabricant/fabricants.html.twig', [
            'fabricants' => $fabricants,
            'count' => $fabricants->getTotalItemCount(),
            'form'=>$form->createView()
        ]);
    }




    /**
     * @Route("/admin/fabricant/{id}/edit", name="fabricant_edit", methods="GET|POST")
     */
    public function edit(Request $request, Manufacturer $manufacturer)
    {
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $metadata = $this->em->getClassMetaData(get_class($manufacturer));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fabricant_show', [
                'id' => $manufacturer->getId()
            ]);
        }

        return $this->render('admin/fabricant/fabricant_edit.html.twig', [
            'fabricant' => $manufacturer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/fabricant/new", name="fabricant_new")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $metadata = $this->em->getClassMetaData(get_class($manufacturer));
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new AssignedGenerator());

            // 4) save the manufacturer
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($manufacturer);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('fabricant_show');
        }

        return $this->render(
            'admin/fabricant/new_fabricant.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/fabricant/{id}/delete", name="fabricant_delete", methods="DELETE")
     */
    public function delete(Request $request, Manufacturer $manufacturer)
    {
        if ($this->isCsrfTokenValid('delete' . $manufacturer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($manufacturer);
            $em->flush();
        }

        return $this->redirectToRoute('fabricant_show');
    }


}
