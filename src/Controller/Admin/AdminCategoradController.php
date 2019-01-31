<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;

/**
 * Description of CategoryController
 *
 * @author Hubsine
 */
class AdminCategoradController extends Controller{

    /**
     * @Route("/admin/category/new", name="category_new")
     */
    public function newAction(Request $request){

        $categoryFormType = $this->get('app.form.type.about_users');

        $category = new Category();

        $form = $this->createForm($categoryFormType, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'La catégorie est ajoutée avec succès.');

            // Re-initialise le formulaire pour pouvoir ajouter une nouvelle catégorie
            // Vous pouvez aussi rediriger vers une page

            $form = $this->createForm($categoryFormType, new Category());
        }

        return $this->render('admin/categorie/category_new.html.twig', array(
            'form'  => $form->createView()
        ));

    }
}