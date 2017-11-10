<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 24.10.17
 * Time: 17:36
 */

namespace OSEL\MusicsheetBundle\Controller;

use OSEL\MusicsheetBundle\Entity\Parts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TestController extends Controller
{
    public function changeURLAction(Request$request)
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_WEBMASTER')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }


        $parts = $this->getDoctrine()->getManager()->getRepository(Parts::class)->findAll();
        $em = $this->getDoctrine()->getManager();


        foreach ($parts as $part)
        {
            $partUrl = $part->getUrl();
            $partUrl = str_replace('bundles/musicsheets/index', 'library', $partUrl);
            $part->setUrl($partUrl);
            $em->persist($part);
        }
        $em->flush();


        $request->getSession()->getFlashBag()->add('notice', 'Partitions: changement d\'url effectué');



        return $this->redirect($this->generateUrl('osel_core_home'));


    }
}