<?php

namespace OSEL\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELCoreBundle:Core:index.html.twig');
    }
}
