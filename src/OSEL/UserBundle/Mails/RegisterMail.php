<?php
// src/OSEL/UserBundle/Mails

namespace OSEL\UserBundle\Mails;

class RegisterMail extends \Twig_Extension
{
    private $mailer;
    private $templateService;

    public function __construct(\Swift_Mailer $mailer, $template)
    {
        $this->mailer          = $mailer;
        $this->templateService = $template;
    }


    public function sendRegisterMail($name, $id, $sha, $userName, $mail, $user)
    {
        //on envoie un mail
        $message = \Swift_Message::newInstance()
            ->setSubject('Inscription à l\'Osel!')
            ->setFrom('inscription@osel.be')
            ->setTo($mail)
            ->setBody(
                $this->templateService->render(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/registration.html.twig', array(
                    'name'      => $name,
                    'user'      => $user,
                    'idUser'    => $id,
                    'sha'       => $sha,
                    'userName'  => $userName)),
                'text/html'
            );
        $this->mailer->send($message);
    }
}