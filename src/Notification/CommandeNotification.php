<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 13/12/2018
 * Time: 14:17
 */

namespace App\Notification;

use App\Entity\Commande;
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
     * ContactNotification constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $renderer
     */
    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Commande $commande){
        $message= (new \Swift_Message('[SNIW, centrale d\'achat export.] New order : ' . $commande->getReference()))
            ->setFrom($commande->getUtilisateur()->getEmail())
            ->setTo($commande->getUtilisateur()->getEmail())
            ->setReplyTo($commande->getUtilisateur()->getEmail())
            ->setBody($this->renderer->render('emails/commande.html.twig', [
                'commande'=>$commande
            ]),'text/html');
        $this->mailer->send($message);
    }
}