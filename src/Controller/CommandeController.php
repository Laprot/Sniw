<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Notification\CommandeNotification;
use App\Security\AppAccess;
use Doctrine\Common\Persistence\ObjectManager;
use Imagine\Exception\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class CommandeController extends AbstractController
{
    private $securityChecker;
    private $token;

    public function __construct(AuthorizationCheckerInterface $securityChecker, TokenStorageInterface $token)
    {
        $this->securityChecker = $securityChecker;
        $this->token = $token;
    }


    public function facture(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        //génère un token
        $generator = random_bytes(20);
        $panier = $session->get('panier');
        $commande = [];
        $totalHT = 0;
        $produits = $em->getRepository(Produit::class)->findArray(array_keys($session->get('panier')));

        //On rentre le prix et recapitulatif des produits achetés dans le tableau commande
        foreach($produits as $produit) {
            $prixHT = ($produit->getPrixFinal() * $panier[$produit->getId()]);
            $totalHT += $prixHT;

            $commande['produit'][$produit->getId()] = [
                'reference' => $produit->getReference(),
                'nom' => $produit->getNom(),
                'prixUnitaire' => $produit->getPrixFinal(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrixFinal(), 2),
                'image' => $produit->getImage(),
                'imageImport' => $produit->getImageImport(),
                'id' => $produit->getId()
            ];
        }
        $commande['nom'] = $session->get('nom');
        $commande['prixHT'] = round($totalHT, 2);
        $commande['token'] = bin2hex($generator);

        return $commande;

    }

    public function prepareCommande(Request $request){
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if(!$session->has('commande')) {
            $commande = new Commande();
        } else {
            $commande = $em->getRepository(Commande::class)->find($session->get('commande'));
        }
        $commande->setDate(new \DateTime('now'));

        //On récupère l'utilisateur
        $user = $this->token->getToken()->getUser();

        //Informations clients adresse

        $commande->setSociete($user->getSociete());
        $commande->setPrenom($user->getPrenom());
        $commande->setNom($user->getNom());
        $commande->setEmail($user->getEmail());
        $commande->setAdresse($user->getAdresse());
        $commande->setCodePostal($user->getCodePostal());
        $commande->setTelephone($user->getTelephone());
        $commande->setVille($user->getVille());
        $commande->setPays($user->getPays());
        $commande->setUtilisateur($user);

        $commande->setValider(0);




        // Référence aléatoire de 8 lettres
        $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_aleatoire = '';
        for($i=0;$i<8;$i++){
            $code_aleatoire .= $characts[ rand() % strlen($characts) ];
            $commande->setReference($code_aleatoire);
        }

        //On ajoute le tableau commande via la fonction facture() situé au dessus
        $commande->setCommande($this->facture($request));

        if(!$session->has('commande')){
            $em->persist($commande);
            $session->set('commande',$commande);
        }


        $em->flush();



        //Une fois la commande passée, on supprime la commande et le panier de la session
        $session->remove('commande');
        $session->remove('panier');

        $this->get('session')->getFlashBag()->add('success','Votre demande sur SNIW, centrale d\'achat export. a bien été enregistrée.');

        return new Response($commande->getId());

    }



    /**
     * @Route("/panier/validation", name="validation")
     */
    public function validation(Request $request, CommandeNotification $notification) {
        //on prépare la commande
        $prepareCommande = $this->prepareCommande($request);
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($prepareCommande->getContent());

        //et on envoie un mail aux reponsables export pour facture

        // /!\ On n'envoie pas de mails aux reponsables si l'admin crée une commande pour une commande type
        if(!$this->isGranted('ROLE_ADMIN') ) {
            $notification->notify($commande);
        }

        return $this->render('panier/validation.html.twig', [
            'commande' => $commande
        ]);
    }
}