<?php

namespace App\Controller\CompteClient;

use App\Entity\User;
use App\Form\AdresseUserType;
use App\Form\UserType;
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
}
