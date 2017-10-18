<?php

namespace OSELWeekendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELWeekendBundle:Default:index.html.twig');
    }
}
