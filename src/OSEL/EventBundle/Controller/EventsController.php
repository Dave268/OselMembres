<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 09.10.17
 * Time: 19:55
 */

namespace OSEL\EventBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OSEL\EventBundle\Entity\Event;
use OSEL\EventBundle\Form\ActivateEventType;
use OSEL\EventBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EventsController extends Controller
{
    public function indexAction($page, $criteria, $desc, $active, $nbPerPage, Request $request)
    {

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
            if ($page < 0) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            //$listPlaces = $this->getDoctrine()->getManager()->getRepository(Place::class)->getPlaces($page, $nbPerPage, $criteria, $desc);
            $listEvents = $this->getDoctrine()->getManager()->getRepository(Event::class)->findBy(array(), array('dateAdd' => 'DESC'));


            $nbPages = ceil(count($listEvents) / $nbPerPage);
            if ($page > $nbPages) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            return $this->render('OSELEventBundle:events:index.html.twig', array(
                'listEvents' => $listEvents,
                'page' => $page,
                'criteria' => $criteria,
                'desc' => $desc,
                'active' => $active,
                'nbPages' => $nbPages));
    }

    public function viewAction($id)
    {
        if($id == 0)
        {
            $event = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->findOneBy(array('active' => 1));
            if($event === null)
            {
                return $this->render('OSELEventBundle:events:noevent.html.twig');
            }
        }
        else{
            $event = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->findOneBy(array('id' => $id));
        }
        return $this->render('OSELEventBundle:events:view.html.twig', array(
            'weekend'   => $event));
    }

    public function addAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND'))
        {
            $event = new Event();
            $form = $this->get('form.factory')->create(EventType::class, $event);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    if($event->getFile() != NULL)
                    {
                        $event->uploadImage();
                    }

                    $em = $this->getDoctrine()->getManager();

                    $events = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->findAll();

                    foreach ($events as $t){
                        $t->setActive(0);
                        $em->persist($t);
                    }

                    $em->flush();

                    foreach ($event->getSubEvents() as $sub) {
                        $sub->setEvent($event);
                        $em->persist($sub);
                    }

                    $em->persist($event);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Un nouveau Weekend a bien été ajouté à la base de données');
                    return $this->redirect($this->generateUrl('osel_event_list'));

                } catch (UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce weekend existe déjà dans la base de données!');
                }
            }
            return $this->render('OSELEventBundle:events:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Ajouter un weekend',
                'selectedPage' => 'weekend'
            ));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function modifyAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND'))
        {
            $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->find($id);
            $form = $this->get('form.factory')->create(EventType::class, $event);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    if($event->getFile() != NULL)
                    {
                        $event->uploadImage();
                    }

                    $em = $this->getDoctrine()->getManager();

                    foreach ($event->getSubEvents() as $sub)
                    {
                        $sub->setEvent($event);
                        $em->persist($sub);
                    }
                    $em->persist($event);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Un nouveau Weekend a bien été ajouté à la base de données');
                    return $this->redirect($this->generateUrl('osel_event_list'));

                } catch (UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce weekend existe déjà dans la base de données!');
                }
            }
            return $this->render('OSELEventBundle:events:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Ajouter un weekend',
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
            $event = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->findOneBy(array('id' => $id));
            $subevents = $event->getSubEvents();
            $em = $this->getDoctrine()->getManager();

            foreach ($subevents as $sub)
            {
                $em->remove($sub);
            }
            $em->remove($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Le weekend a été supprimé');

            return $this->redirect($this->generateUrl('osel_event_list'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function activateEventAction($id, Request $request)
    {
        $event = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->find($id);

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND'))
        {
            $form = $this->get('form.factory')->create(ActivateEventType::class, $event);

            if ($form->handleRequest($request)->isValid()) {


                $em = $this->getDoctrine()->getManager();

                if($event->getActive())
                {
                    $events = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Event')->findAll();
                    foreach ($events as $e)
                    {
                        $e->setActive(false);
                        $em->persist($e);
                    }
                    $event->setActive(true);
                }

                $em->persist($event);
                $em->flush();

                if($request->isXmlHttpRequest())
                {
                    $json = json_encode(array(
                        'id'    => $event->getId(),
                        'actif' => $event->getActive(),
                    ));

                    $response = new Response($json);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }

                $request->getSession()->getFlashBag()->add('success', 'L\'Evenement a bient été activé/désactivé');

                return $this->redirect($this->generateUrl('osel_event_list'));
            }
        }

        return $this->render('OSELEventBundle:inscriptions:payement.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}