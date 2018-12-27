<?php

namespace App\Controller\CompteClient;

use App\Entity\User;
use App\Form\UserType;
use App\Security\AppAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompteClientController extends AbstractController
{
    /**
     * @Route("/infos/{id}/edit", name="edit_profil", methods="GET|POST")
     */
    public function edit(Request $request, User $user, $id)
    {
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Votre profil a bien été modifié');
            return $this->redirectToRoute('edit_profil', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('compte_client/infos.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
