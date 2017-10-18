<?php

namespace OSEL\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OSELCoreBundle:Default:index.html.twig');
    }
}
