<?php

namespace App\Controller;

use App\Entity\Coccinews;
use App\Entity\Upload;
use App\Form\CoccinewsType;
use App\Form\UploadType;
use App\Repository\CoccinewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Finder\Finder;

class LogistiqueController extends AbstractController
{
    /**
     * @Route("/logistique", name="logistique")
     */
    public function index()
    {
        return $this->render('logistique/logistique.html.twig');
    }

    /**
     * @Route("/services", name="services")
     */
    public function services() {
        return $this->render('logistique/services.html.twig');
    }


    /**
     * @Route("/conseils", name="conseils")
     */
    public function conseils() {
        return $this->render('logistique/conseils.html.twig');
    }

    /**
     * @Route("/cocci-news", name="cocci_news_show")
     */
    public function showAction() {

        $uploads = $this->getDoctrine()->getRepository(Coccinews::class)->findAll();

        return $this->render('logistique/cocciNews.html.twig', [
            'uploads'=>$uploads
        ]);
    }

    /**
     * @Route("/cocci-news/new", name="cocci_news_new")
     */
    public function cocciNews(Request $request) {
        $coccinews = new Coccinews();
        $form = $this->createForm(CoccinewsType::class,$coccinews);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $coccinews->getPicture();
            $fileName = md5(uniqid().'.'. $file->guessExtension());

            $file->move($this->getParameter('upload_cocci_news_directory'),$fileName);

            $this->addFlash('success','Votre fichier a bien été uploadé');
            $coccinews->setPicture($fileName);

            $em->persist($coccinews);
            $em->flush();

            return $this->redirectToRoute('cocci_news_show', [
                'fileName' => $fileName,
            ]);

        }

        return $this->render('logistique/new_cocciNews.html.twig', [
            'formUpload'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/cocci-news/delete/{id}", name="cocci_news_delete", methods="DELETE")
     */
    public function delete(Request $request, Coccinews $coccinews)
    {
        if ($this->isCsrfTokenValid('delete' . $coccinews->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coccinews);
            $em->flush();
        }
        return $this->redirectToRoute('cocci_news_show');
    }





}
