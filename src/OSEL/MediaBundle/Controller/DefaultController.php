<?php

namespace OSEL\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELMediaBundle:Default:index.html.twig');
    }
}
