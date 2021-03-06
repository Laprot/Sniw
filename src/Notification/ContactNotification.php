<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 13/12/2018
 * Time: 14:17
 */

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
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


    public function notify(Contact $contact){
        $message= (new \Swift_Message('Agence : ' . $contact->getSociete()))
            ->setFrom($contact->getEmail())
            ->addTo('fdelhaye@sniw.fr')
            ->setReplyTo('centrale.sniw@gmail.com')
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact'=>$contact
            ]),'text/html');
        $this->mailer->send($message);
    }
}