<?php

namespace OSEL\DocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELDocBundle:Default:index.html.twig');
    }
}
