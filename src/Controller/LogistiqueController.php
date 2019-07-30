<?php

namespace App\Controller;

use App\Entity\Coccinews;
use App\Entity\Formation;
use App\Entity\Upload;
use App\Form\CoccinewsType;
use App\Form\FormationType;
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
     * @Route("/informations", name="informations_show")
     */
    public function showAction() {
        $uploads = $this->getDoctrine()->getRepository(Coccinews::class)->findAll();

        return $this->render('logistique/informations.twig', [
            'uploads'=>$uploads
        ]);
    }

    /**
     * @Route("/informations/new", name="information_new")
     */
    public function informationNews(Request $request) {
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

            return $this->redirectToRoute('informations_show', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('logistique/new_information.html.twig', [
            'formUpload'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/informations/delete/{id}", name="informations_delete", methods="DELETE")
     */
    public function delete(Request $request, Coccinews $coccinews)
    {
        if ($this->isCsrfTokenValid('delete' . $coccinews->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($coccinews);
            $em->flush();
        }
        return $this->redirectToRoute('informations_show');
    }


    /**
     * @Route("/formations/show", name="formations_show")
     */
    public function showFormations() {

        $uploads = $this->getDoctrine()->getRepository(Formation::class)->findAll();

        return $this->render('logistique/formations.twig', [
            'uploads'=>$uploads
        ]);
    }

    /**
     * @Route("/formations/new", name="formation_new")
     */
    public function formationNews(Request $request) {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class,$formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $formation->getPicture();
            $fileName = md5(uniqid().'.'. $file->guessExtension());

            $file->move($this->getParameter('upload_formations_directory'),$fileName);

            $this->addFlash('success','Votre fichier a bien été uploadé');
            $formation->setPicture($fileName);

            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formations_show', [
                'fileName' => $fileName,
            ]);
        }

        return $this->render('logistique/new_formation.html.twig', [
            'formUpload'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/formations/delete/{id}", name="formations_delete", methods="DELETE")
     */
    public function deleteFormation(Request $request, Formation $formation)
    {
        if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formation);
            $em->flush();
        }
        return $this->redirectToRoute('formations_show');
    }
}
