<?php

namespace OSEL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    // La page d'accueil
    public function indexAction(Request $request)
    {
        return $this->redirect($this->generateUrl('osel_core_agenda'));
    }

    public function agendaAction()
    {
        return $this->get('templating')->renderResponse('OSELCoreBundle:Core:agenda.html.twig');
    }

    public function constructionAction(Request $request)
    {
        return $this->get('templating')->renderResponse('OSELCoreBundle:construction:construction.html.twig');
    }
}