<?php

namespace OSEL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    // La page d'accueil
    public function indexAction(Request $request)
    {
        $request->getSession()->getFlashBag()->add('Error', 'Ce username ou mail est incorrect');

        return $this->get('templating')->renderResponse('OSELCoreBundle:Core:index.html.twig', array(
		'selectedPage' => 'home'));
    }

    public function agendaAction()
    {
        return $this->get('templating')->renderResponse('OSELCoreBundle:Core:agenda.html.twig',array(
		'selectedPage' => 'agenda'));
    }

    public function testMailAction(Request $request)
    {

        //on envoie un mail
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('do_not_reply@osel.be')
            ->setTo('davidgoubau@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/registration.html.twig',
                    array('name' => 'David')
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $request->getSession()->getFlashBag()->add('notice', 'Mail Envoyé');



        return $this->get('templating')->renderResponse('OSELCoreBundle:Core:index.html.twig', array(
            'selectedPage' => 'home'));
    }

    public function constructionAction(Request $request)
    {
        return $this->get('templating')->renderResponse('OSELCoreBundle:construction:construction.html.twig', array(
            'selectedPage' => 'home'));
    }
}