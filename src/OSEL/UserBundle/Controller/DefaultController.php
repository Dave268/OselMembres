<?php

namespace OSEL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELUserBundle:Default:index.html.twig');
    }
}
