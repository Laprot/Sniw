<?php

namespace App\Controller\Admin;



use App\Entity\Upload;
use App\Form\UploadType;
use App\Import\ImportProduit;
use Doctrine\Common\Persistence\ObjectManager;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdministrationController extends AbstractController
{

    /**
     *@Route("/admin/upload",name="admin_upload")
     */
    public function upload(Request $request){
        $upload = new Upload();
        $form = $this->createForm(UploadType::class,$upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
            $fileName = $file->guessExtension();
            $path = $file->move($this->getParameter('upload_directory'),$fileName);

            $path->file_path = $file->getRealPath();


            $csvPath = $path->file_path;


            $reader = Reader::createFromPath($csvPath);


            $this->addFlash('success','Votre fichier a bien été uploadé');
            $upload->setName($fileName);





            return $this->redirectToRoute('admin_upload', [
                'fileName' => $fileName
            ]);
        }


        return $this->render('admin/administration/admin.html.twig', [
            'formUpload'=>$form->createView(),
            ''
        ]);
    }





}