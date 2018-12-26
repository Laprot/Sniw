<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 13/12/2018
 * Time: 14:17
 */

namespace App\Notification;

use App\Entity\DemandeCompte;
use Twig\Environment;

class OuvertureCompteNotification
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

    public function notify(DemandeCompte $demandeCompte){
        $message= (new \Swift_Message('Agence : ' . $demandeCompte->getSociete()))
            ->setFrom($demandeCompte->getEmail())
            ->setTo($demandeCompte->getEmail())
            ->setReplyTo($demandeCompte->getEmail())
            ->setBody($this->renderer->render('emails/demande_compte.html.twig', [
                'contact'=>$demandeCompte
            ]),'text/html');
        $this->mailer->send($message);
    }
}