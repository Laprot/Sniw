<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 13/12/2018
 * Time: 14:17
 */

namespace App\Notification;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Twig\Environment;

class CommandeNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(\Swift_Mailer $mailer, Environment $renderer,EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Commande $commande){
        foreach ($commande->getCommande()['produit'] as $categorie) {
            $c = $categorie['categories'][0]->getNom();
            $categories = $this->em->getRepository(Categorie::class)->findBy(['nom' => $c ]);
        }

        $user_commandes = $this->em->getRepository(User::class)->findBy(['nom' => $commande->getNom()]);

        foreach($user_commandes as $user_commande) {
            $id_groupe = $user_commande->getIdGroupe()->getId();
        }

        $message= (new \Swift_Message('[SNIW, centrale d\'achat export.] New order : ' . $commande->getReference()))
            ->setFrom($commande->getUtilisateur()->getEmail())
            ->setTo('fievet.pierro@hotmail.fr')
            ->setReplyTo('fievet.pierro@hotmail.fr')
            ->setBody($this->renderer->render('emails/commande.html.twig', [
                'commande'=>$commande,
                'categories' => $categories,
                'id_groupe' => $id_groupe
            ]),'text/html');
        $this->mailer->send($message);
    }
}