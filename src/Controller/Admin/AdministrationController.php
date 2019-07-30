<?php

namespace App\Controller\Admin;



use App\Entity\Produit;
use App\Entity\Upload;
use App\Form\UploadCommandeType;
use App\Form\UploadImageType;
use App\Form\UploadType;
use App\Import\ImportProduit;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Exception\InvalidArgumentException;


class AdministrationController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     *@Route("/admin/upload",name="admin_upload")
     */
    public function upload(Request $request){
        $upload = new Upload();
        $form = $this->createForm(UploadType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
           // $fileName = '1'.md5(uniqid()).'.'.$file->guessExtension();

            $fileName = $upload->getName()->getClientOriginalName();


            if($file->guessExtension() != "txt") {
                $this->addFlash('error','Ficher CSV demandé');
            }
            else {
                $file->move($this->getParameter('upload_directory'),$fileName);
                $this->addFlash('success','Votre fichier a bien été uploadé');
            }
            return $this->redirectToRoute('admin_upload', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('admin/administration/import-produits.html.twig', [
            'formUpload'=>$form->createView(),

        ]);
    }


    /**
     *@Route("/admin/uploadImages",name="admin_upload_images")
     */
    public function uploadImages(Request $request){
        $upload = new Upload();
        $form = $this->createForm(UploadImageType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            //$fileName = '1'.md5(uniqid()).'.'.$file->guessExtension();

            //UploadDirectory
            $uploadDir = $this->getParameter('images_directory');

            $files = $request->files->get('upload_image')['name'];


            foreach ($files as $file ){
                $fileName = $file->getClientOriginalName();

                $file->move($uploadDir, $fileName);


                $gencod = explode(".",$fileName);

                $produits = $this->em->getRepository(Produit::class)->findBy(['Gencod' => $gencod[0]]);

                foreach($produits as $prod) {
                    if((sizeof($prod->getImage()) == 0 ) && sizeof($prod->getImageImport() > 0)) {
                        $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                        $prod->setImageImport(null);
                        $this->em->persist($prod);
                        $this->em->flush();
                    }
                    if((sizeof($prod->getImage()) > 0 ) || sizeof($prod->getImageImport() > 0)) {
                        $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                        $prod->setImageImport(null);
                        $this->em->persist($prod);
                        $this->em->flush();
                    }
                    if((sizeof($prod->getImage()) == 0 ) && sizeof($prod->getImageImport() == 0)) {
                        $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                        $prod->setImageImport(null);
                        $this->em->persist($prod);
                        $this->em->flush();
                    }
                }


            }


            $this->addFlash('success', 'Vos fichiers ont bien été uploadés');

            return $this->redirectToRoute('admin_upload_images', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('admin/administration/import-images.html.twig', [
            'formUpload'=>$form->createView(),

        ]);
    }

    /**
     *@Route("/admin/upload_commandes",name="admin_upload_commandes")
     */
    public function uploadCommandes(Request $request){
        $upload = new Upload();
        $form = $this->createForm(UploadCommandeType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
            // $fileName = '1'.md5(uniqid()).'.'.$file->guessExtension();


            $fileName = $upload->getName()->getClientOriginalName();


            if($file->guessExtension() != "txt") {
                $this->addFlash('error','Ficher CSV demandé');
            }
            else {
                $file->move($this->getParameter('upload_commandes_directory'),$fileName);
                $this->addFlash('success','Votre fichier a bien été uploadé');
            }
            return $this->redirectToRoute('admin_upload_commandes', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('admin/administration/import-commandes.html.twig', [
            'formUpload'=>$form->createView(),

        ]);
    }

    /**
     *@Route("/admin/importImageMassif",name="admin_import_images")
     */
    public function importImages(){
        $finder = new Finder();


        //EN DEV
        //$finder->in(__DIR__.'/../../../public/produits/images');


        //EN PROD
        $finder->in('/home/centralacexpcom/www/public/produits/images');


        foreach($finder as $file) {
            $fileName = $file->getFilename();
            $gencod = explode(".",$fileName);
            $produits = $this->em->getRepository(Produit::class)->findBy(['Gencod' => $gencod[0]]);
            foreach($produits as $prod) {
                /*
                if(($prod->getImage() > 0 ) && ($prod->getImageImport() > 0)) {
                    $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                    $prod->setImageImport(null);
                    $this->em->persist($prod);
                    $this->em->flush();
                }*/

                //Permet d'envoyer les images s'il n'y a pas d'image

                if(($prod->getImage()) == 0  || ($prod->getImageImport() > 0)) {
                    $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                    $prod->setImageImport(null);
                    $this->em->persist($prod);
                    $this->em->flush();
                }
                if(($prod->getImage() == 0 ) && ($prod->getImageImport() == 0)) {
                    $prod->setImage('/' . $gencod[0] . '.' . $gencod[1]);
                    $prod->setImageImport(null);
                    $this->em->persist($prod);
                    $this->em->flush();
                }
            }
        }


        $this->addFlash('success', 'Vos fichiers ont bien été importés');

        return $this->redirectToRoute('admin_upload_images', [
            'fileName' => $fileName,
        ]);
    }



    /**
     *@Route("/admin/maj_etat",name="maj_etat")
     */
    public function majEtat(Request $request) {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
            // $fileName = '1'.md5(uniqid()).'.'.$file->guessExtension();

            $fileName = $upload->getName()->getClientOriginalName();


            if($file->guessExtension() != "txt") {
                $this->addFlash('error','Ficher CSV demandé');
            }
            else {
                $file->move($this->getParameter('upload_etat_directory'),$fileName);
                $this->addFlash('success','Votre fichier a bien été uploadé');
            }
            return $this->redirectToRoute('maj_etat', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('admin/administration/maj-etat-produits.html.twig', [
            'formUpload'=>$form->createView(),

        ]);
    }

    /**
     *@Route("/admin/maj_prix",name="maj_prix")
     */
    public function majPrix(Request $request) {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
            // $fileName = '1'.md5(uniqid()).'.'.$file->guessExtension();

            $fileName = $upload->getName()->getClientOriginalName();


            if($file->guessExtension() != "txt") {
                $this->addFlash('error','Ficher CSV demandé');
            }
            else {
                $file->move($this->getParameter('upload_prix_directory'),$fileName);
                $this->addFlash('success','Votre fichier a bien été uploadé');
            }
            return $this->redirectToRoute('maj_prix', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('admin/administration/maj-prix-produits.html.twig', [
            'formUpload'=>$form->createView(),

        ]);
    }



}