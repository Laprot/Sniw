<?php

namespace App\Controller\Comptes;

use App\Entity\Commande;
use App\Entity\CommandeTypeProduits;
use App\Entity\User;
use App\Form\AdresseUserType;
use App\Form\CommandeTypeProduitsType;
use App\Form\UserType;
use App\Repository\CommandeRepository;
use App\Security\AppAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompteClientController extends AbstractController
{
    /**
     * @Route("/infos/{id}/edit", name="edit_profil", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil a bien été modifié');
            return $this->redirectToRoute('edit_profil', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('compte/infos.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/infos/adresse/{id}/edit", name="edit_adresse", methods="GET|POST")
     */
    public function adresse(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);
        $form = $this->createForm(AdresseUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre adresse a bien été modifié');
            return $this->redirectToRoute('edit_adresse', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('compte/adresse.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/infos/{id}/commandes", name="commande_view")
     */
    public function commande(User $user){

        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);

        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();


        return $this->render('compte/commande.html.twig', [
            'user' => $user,
            'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/infos/{id}/commande/detail", name="commande_details")
     */
    public function detailsCommande(User $user,Commande $commande) {

        //$commande = $this->getDoctrine()->getRepository(Commande::class)->findByUser($user);

        $commandeUser = $commande->getUtilisateur()->getId();

       // $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $commandeUser);

        return $this->render('compte/details-commande.html.twig', [
            'user'=>$user,
            'commande'=>$commande
        ]);
    }



    /**
     * @Route("/infos/{id}/commande-type/detail", name="commandes-type_details")
     */
    public function detailsCommandeType(User $user,Commande $commande) {

        //$commande = $this->getDoctrine()->getRepository(Commande::class)->findByUser($user);

        return $this->render('compte/details-commandes-types.html.twig', [
            'user'=>$user,
            'commande'=>$commande
        ]);
    }

}
