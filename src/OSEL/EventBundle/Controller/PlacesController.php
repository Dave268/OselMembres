<?php

namespace OSEL\EventBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OSEL\EventBundle\Entity\Place;
use OSEL\EventBundle\Form\PlaceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlacesController extends Controller
{
    public function indexPlacesAction($page, $criteria, $desc, $active, $nbPerPage, Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND')) {
            if ($page < 1) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            //$listPlaces = $this->getDoctrine()->getManager()->getRepository(Place::class)->getPlaces($page, $nbPerPage, $criteria, $desc);
            $listPlaces = $this->getDoctrine()->getManager()->getRepository(Place::class)->findAll();


            $nbPages = ceil(count($listPlaces) / $nbPerPage);
            if ($page > $nbPages) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Cette page n\'existe pas');
            }


            return $this->render('OSELEventBundle:places:listplaces.html.twig', array(
                'listPlaces' => $listPlaces,
                'page' => $page,
                'criteria' => $criteria,
                'desc' => $desc,
                'active' => $active,
                'nbPages' => $nbPages,
                'selectedPage' => 'weekend'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function viewPlaceAction($id)
    {
        $lieu = $this->getDoctrine()->getManager()->getRepository(Place::class)->find($id);
        return $this->render('OSELEventBundle:places:view.html.twig', array(
            'lieu'          => $lieu,
            'selectedPage'  => 'weekend'
        ));
    }

    public function addPlaceAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND'))
        {
            $place = new Place();
            $form = $this->get('form.factory')->create(PlaceType::class, $place);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    if($place->getFile() != NULL)
                    {
                        $place->uploadImage();
                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($place);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('notice', 'Un nouveau lieu a bien été rajouté à la base de données');
                    return $this->redirect($this->generateUrl('osel_event_list_places'));

                } catch (UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce lieu existe déjà dans la base de données!');
                }
            }
            return $this->render('OSELEventBundle:places:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Ajouter un lieu',
                'selectedPage' => 'weekend'
            ));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function modifyPlaceAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEEKEND'))
        {
            $place =$this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Place')->findOneBy(array('id' => $id));
            $form = $this->get('form.factory')->create(PlaceType::class, $place);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                try {
                    if($place->getFile() != NULL)
                    {
                        $place->uploadImage();
                    }


                    $em = $this->getDoctrine()->getManager();
                    $em->persist($place);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('notice', 'Le Lieu a bien été modifié');
                    return $this->redirect($this->generateUrl('osel_event_list_places'));

                } catch (UniqueConstraintViolationException $e) {
                    $request->getSession()->getFlashBag()->add('Error', 'Ce lieu existe déjà dans la base de données!');
                }
            }
            return $this->render('OSELEventBundle:places:form.html.twig', array(
                'form'         => $form->createView(),
                'title'        => 'Modifier un lieu',
                'selectedPage' => 'weekend'
            ));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }

    public function deletePlaceAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEBMASTER'))
        {
            $place = $this->getDoctrine()->getManager()->getRepository('OSELEventBundle:Place')->findOneBy(array('id' => $id));
            $em = $this->getDoctrine()->getManager();
            $em->remove($place);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Le lieu a été supprimé');

            return $this->redirect($this->generateUrl('osel_event_list_places'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }
}
