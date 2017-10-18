<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 18.10.17
 * Time: 15:37
 */

namespace OSEL\EventBundle\Controller;

use OSEL\EventBundle\Entity\SubEvents;
use OSEL\EventBundle\Entity\SubscribeEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TestController extends Controller
{
    public function nbsubscriptionsAction(Request$request)
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND')) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }


            $subevents = $this->getDoctrine()->getManager()->getRepository(SubEvents::class)->findAll();
            $em = $this->getDoctrine()->getManager();


            foreach ($subevents as $subEvent)
            {
                $nb = null;
                $nb = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbSubscriptionsEvent($subEvent->getId());
                    $subEvent->setNbSubscriptions($nb);
                    $em->persist($subEvent);
                    $em->flush();

            }
        $request->getSession()->getFlashBag()->add('notice', 'nbSubscriptions test effectué');



        return $this->redirect($this->generateUrl('osel_core_home'));


    }
}