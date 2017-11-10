<?php
/**
 * Created by PhpStorm.
 * User: davidgoubau
 * Date: 02/06/2017
 * Time: 14:58
 */

// src/OSEL/UserBundle/Mails

namespace OSEL\UserBundle\Mails;

class ResetMail extends \Twig_Extension
{
    private $mailer;
    private $templateService;

    public function __construct(\Swift_Mailer $mailer, $template)
    {
        $this->mailer          = $mailer;
        $this->templateService = $template;
    }


    public function sendRegisterMail($name, $id, $sha, $userName, $mail)
    {
        //on envoie un mail
        $message = \Swift_Message::newInstance()
            ->setSubject('Inscription à l\'Osel!')
            ->setFrom('noreply@osel.be')
            ->setTo($mail)
            ->setBody(
                $this->templateService->render(
                // app/Resources/views/Emails/resetMail.html.twig
                    'Emails/resetMail.html.twig', array(
                    'name'      => $name,
                    'idUser'    => $id,
                    'sha'       => $sha,
                    'userName'  => $userName)),
                'text/html'
            );
        $this->mailer->send($message);
    }
}