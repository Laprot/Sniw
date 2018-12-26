<?php

namespace App\Controller;

use App\Entity\DemandeCompte;
use App\Form\DemandeCompteType;
use App\Notification\OuvertureCompteNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DemandeCompteController extends AbstractController
{
    /**
     * @Route("/ouverture-compte", name="creation_compte", methods="GET|POST")
     */
    public function index(Request $request,OuvertureCompteNotification $notification)
    {
        $demande = new DemandeCompte();
        $form = $this->createForm(DemandeCompteType::class,$demande);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $notification->notify($demande);
            $this->addFlash('success','Votre demande de création de compte a bien été envoyée');
            return $this->redirectToRoute('creation_compte');
        }

        return $this->render('contact/demande_compte.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

}
