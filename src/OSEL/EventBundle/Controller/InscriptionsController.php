<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 09.10.17
 * Time: 21:19
 */

namespace OSEL\EventBundle\Controller;

use OSEL\EventBundle\Entity\Event;
use OSEL\EventBundle\Entity\SubscribeEvent;
use OSEL\EventBundle\Form\PaymentCompleteType;
use OSEL\EventBundle\Form\SubscribeEventIndividualType;
use OSEL\EventBundle\Form\SubscribeEventType;
use OSEL\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InscriptionsController extends Controller
{
    public function indexAction($id, $page, $criteria, $desc, $presence, $nbPerPage, Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND')) {
            if ($page < 1) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            $listInscriptions = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getSubscriptions($id, $page, $nbPerPage, $criteria, $desc, $presence);
            $price = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getPrice($id);
            $nbSubscribe = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbParticipants($id);
			$nbParticipants = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbSubscriptions($id);
            $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->findOneBy(array('id' => $id));

            $nbSubscribeEvents = array();
            /*
            foreach ($event->getSubEvents() as $subEvent)
            {
                array_push($nbSubscribeEvents, $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbSubscriptionsEvent($subEvent->getId()));
            }
            */


            $subEvents = $event->getSubEvents();

            $nbPages = ceil(count($listInscriptions) / $nbPerPage);
            if ($page > $nbPages) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            return $this->render('OSELEventBundle:inscriptions:index.html.twig', array(
                'listInscriptions'  => $listInscriptions,
                'event'             => $event,
                'listSub'           => $subEvents,
                'id'                => $id,
                'page'              => $page,
                'criteria'          => $criteria,
                'desc'              => $desc,
                'nbPages'           => $nbPages,
				'nbPerPage'			=> $nbPerPage,
				'nbParticipants'	=> $nbParticipants,
                'nbSubscribe'       => $nbSubscribe,
                'nbSubscribeEvents' => $nbSubscribeEvents,
                'presence'          => $presence,
                'prixTotal'         => $price[0],
                'payeTotal'         => $price[1],
                'selectedPage'      => 'weekend'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }
	
	public function indexSimpleAction($id, $page, $criteria, $desc, $presence, $nbPerPage, Request $request)
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND')) {
			$request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
		}
		
		if ($page < 1) {
			$request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
		}


		$listInscriptions = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getSubscriptions($id, $page, $nbPerPage, $criteria, $desc, $presence);
		$price = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getPrice($id);
		$nbSubscribe = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbParticipants($id);
		$nbParticipants = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getNbSubscriptions($id);
		$event = $this->getDoctrine()->getManager()->getRepository(Event::class)->findOneBy(array('id' => $id));


		$nbPages = ceil(count($listInscriptions) / $nbPerPage);
		if ($page > $nbPages) {
			$request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
		}


		return $this->render('OSELEventBundle:inscriptions:index-simple.html.twig', array(
			'listInscriptions'  => $listInscriptions,
			'event'             => $event,
			'id'                => $id,
			'page'              => $page,
			'criteria'          => $criteria,
			'desc'              => $desc,
			'nbPages'           => $nbPages,
			'nbPerPage'			=> $nbPerPage,
			'nbParticipants'	=> $nbParticipants,
			'nbSubscribe'       => $nbSubscribe,
			'presence'          => $presence,
			'prixTotal'         => $price[0],
			'payeTotal'         => $price[1],
			'selectedPage'      => 'weekend'));
      
    }

    public function viewAction($id)
    {
        return $this->render('OSELEventBundle:inscriptions:view.html.twig');
    }

    public function addAction(Request $request)
    {
        $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->findOneBy(array('active' => true));

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
                return $this->redirect($this->generateUrl('osel_event_add_individual_inscription', array('id' => $event->getId())));

            }
            else
            {
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
            }


            $subscription = new SubscribeEvent();
            if($event == null)
            {
                return $this->render('OSELEventBundle:events:noevent.html.twig', array(
                    'selectedPage' => 'weekend'
                ));
            }
            $temp = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->findByUser($user->getId(), $event->getId());

            if($temp != null)
            {
                return $this->redirect($this->generateUrl('osel_event_modify_inscription', array('id' => $temp->getId())));
            }
            $form = $this->get('form.factory')->create(SubscribeEventType::class, $subscription);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    $subscription->setUser($user);
                    $user->addSubscribeEvent($subscription);
                    $subscription->setEvent($event);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($subscription);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'Bravo!!! Vous êtes inscrit au weekend');
                    return $this->redirect($this->generateUrl('osel_event_view'));

                } catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Vous êtes déjà inscrit');
                }
            }
            return $this->render('OSELEventBundle:inscriptions:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Inscription au Weekend',
                'selectedPage' => 'weekend'
            ));

    }

    public function addIndividualAction($id, Request $request)
    {

            $subscription = new SubscribeEvent();
            $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->findOneBy(array('id' => $id));
            if($event == null)
            {
                return $this->render('OSELEventBundle:events:noevent.html.twig', array(
                    'selectedPage' => 'weekend'
                ));
            }

            $form = $this->get('form.factory')->create(SubscribeEventIndividualType::class, $subscription);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    $subscription->setEvent($event);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($subscription);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'Bravo!!! Un nouvel inscrit au weekend');
                    return $this->redirect($this->generateUrl('osel_event_list_inscription', array('id' => $id)));

                } catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce membre est déjà déjà inscrit');
                }
            }
            return $this->render('OSELEventBundle:inscriptions:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Inscription au Weekend',
                'selectedPage' => 'weekend'
            ));

    }

    public function modifyAction($id, $idEvent, Request $request)
    {
        $userId = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->findOneBy(array('id' => $id))->getUser()->getId();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND') || $this->container->get('security.token_storage')->getToken()->getUser()->getId() == $userId)
        {
            $subscription = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->findOneBy(array('id' => $id));
            $form = $this->get('form.factory')->create(SubscribeEventType::class, $subscription);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($subscription);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'L\'inscription  bien été modifié');
                    if(!$this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND') || $idEvent == 0)
                    {
                        return $this->redirect($this->generateUrl('osel_event_view'));
                    }

                    return $this->redirect($this->generateUrl('osel_event_list_inscription', array('id' => $idEvent)));

                } catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce lieu existe déjà dans la base de données!');
                }
            }
            return $this->render('OSELEventBundle:inscriptions:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Inscription au Weekend',
                'selectedPage' => 'weekend'
            ));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function deleteAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEBMASTER'))
        {
            $subscription = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:SubscribeEvent')->findOneBy(array('id' => $id));
            $em = $this->getDoctrine()->getManager();
            $em->remove($subscription);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'L\'inscription de ' . $subscription->getUser()->getLastname() .' ' .$subscription->getUser()->getLastname() . ' weekend a été suprrimé');

            return $this->redirect($this->generateUrl('osel_event_list'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function modifyCompleteAction($id,$idEvent, Request $request)
    {
        $subscription = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:SubscribeEvent')->findOneBy(array('id' => $id));
        $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->find($idEvent);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_TRESORIER'))
        {
            $form = $this->get('form.factory')->create(PaymentCompleteType::class, $subscription);

            if ($form->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($subscription);
                $em->flush();

                $price = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getPrice($id);

                if($request->isXmlHttpRequest())
                {
                    $json = json_encode(array(
                        'id'    => $subscription->getId(),
                        'actif' => $subscription->getPaye(),
                        'paye'  => $price[1]
                    ));

                    $response = new Response($json);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }

                $request->getSession()->getFlashBag()->add('success', 'Un l\'inscription à bien été modifié');

                return $this->redirect($this->generateUrl('osel_event_list_inscription', array('id' => $event->getId())));
            }
        }

        return $this->render('OSELEventBundle:inscriptions:payement.html.twig', array(
            'form' => $form->createView(),
            'selectedPage'	=> 'weekend'
        ));
    }

    public function  modifyPrixAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_TRESORIER'))
        {
            if($id < 1)
            {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }
            else {
                $subscription = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:SubscribeEvent')->find($id);
                $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->findOneBy(array('active' => true));


                $formData = array();
                $form = $this->get('form.factory')->createBuilder(FormType::class, $formData)
                    ->add('prix', TextType::class)
                    ->getForm();

                if ($request->isMethod('POST')) {

                    $form->handleRequest($request);
                    $formData = $form->getData();
                    $em = $this->getDoctrine()->getManager();

                    if ($formData['prix'] != $subscription->getPrix()) {
                        $subscription->setPrix($formData['prix']);
                        $em->persist($subscription);
                        $em->flush();
                    }


                    if ($request->isXmlHttpRequest()) {
                        $price = $this->getDoctrine()->getManager()->getRepository(SubscribeEvent::class)->getPrice($id);

                        $json = json_encode(array(
                            'id' => $subscription->getId(),
                            'paye'  => $price[0]
                        ));

                        $response = new Response($json);
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    }
                    return $this->redirect($this->generateUrl('osel_event_list_inscription', array('id' => $event->getId())));

                }

                return $this->get('templating')->renderResponse('OSELEventBundle:inscriptions:prix.html.twig', array(
                    'form' => $form->createView()));
            }
        }
    }

}