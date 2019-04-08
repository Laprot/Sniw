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

class CompteAdminController extends AbstractController
{
    /**
     * @Route("/infos/{id}/allcommandes/delete", name="commande_delete_admin")
     */
    public function deleteCommandeAdmin(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();

        $this->addFlash('success', 'La commande admin a bien été supprimée');

        return $this->redirectToRoute('commandes_type');
    }


    /**
     * @Route("/infos/{id}/commande/delete", name="commande_delete")
     */
    public function deleteCommande(Request $request, Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();

        $this->addFlash('success', 'La commande  a bien été supprimée');

        return $this->redirectToRoute('commande_view_admin');
    }


    /**
     * @Route("infos/commandes-type", name="commandes_type")
     */
    public function commandeType( Request $request) {
        $commandeType = new CommandeTypeProduits();
        $form = $this->createForm(CommandeTypeProduitsType::class, $commandeType);

        $form->handleRequest($request);

        $commandeTypes = $this->getDoctrine()->getRepository(CommandeTypeProduits::class)->findAll();


        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandeType);
            $entityManager->flush();



            $this->addFlash('success', 'Votre commande type a bien été crée');
            return $this->redirectToRoute('commandes_type');
        }

        return $this->render('compte/commandes-type.html.twig', [
            'form' => $form->createView(),
            'commandeTypes' => $commandeTypes,
            'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/infos/{id}/commandes-type/delete", name="commandes_type_delete")
     */
    public function delete(Request $request, CommandeTypeProduits $commandeTypeProduits)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commandeTypeProduits);
        $em->flush();

        $this->addFlash('success', 'Votre commande type a bien été supprimée. Vous pouvez désormais supprimer la commande qui était associée.');

        return $this->redirectToRoute('commandes_type');
    }



}
