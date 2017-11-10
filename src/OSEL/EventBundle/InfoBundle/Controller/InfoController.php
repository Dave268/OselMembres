<?php

namespace OSEL\InfoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OSEL\InfoBundle\Form\RedirectionMailType;
use OSEL\InfoBundle\Entity\RedirectionMail;


class InfoController extends Controller
{
    public function agendaAction()
    {
        return $this->get('templating')->renderResponse('OSELInfoBundle:Info:agenda.html.twig',array(
            'selectedPage' => 'infos'));
    }

    public function redirectionsAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            $listing = $this->getDoctrine()->getManager()->getRepository('OSELInfoBundle:RedirectionMail')->findAll();

        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');

            return $this->redirect($this->generateUrl('osel_core_home'));
        }

        return $this->get('templating')->renderResponse('OSELInfoBundle:Info:redirections.html.twig',array(
            'selectedPage'  => 'infos',
            'mails'         => $listing));
    }

    public function gestionAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            $listing = $this->getDoctrine()->getManager()->getRepository('OSELInfoBundle:RedirectionMail')->findAll();
            $redirection = new RedirectionMail();
            $form = $this->get('form.factory')->create(RedirectionMailType::class, $redirection);

            if ($form->handleRequest($request)->isSubmitted())
            {
                $em = $this->getDoctrine()->getManager();

                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                {
                    $user = $this->container->get('security.token_storage')->getToken()->getUser();
                    $redirection->setUser($user);
                }


                $em->persist($redirection);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Une redirection mail à été ajoutée.');

                return $this->redirect($this->generateUrl('osel_info_redirections_gestion'));
            }
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');

            return $this->redirect($this->generateUrl('osel_core_home'));
        }


        return $this->get('templating')->renderResponse('OSELInfoBundle:Info:redirections_gestion.html.twig',array(
            'selectedPage'  => 'infos',
            'form'          => $form->createView(),
            'mails'         => $listing));
    }

    public function modifyAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            if($id < 1)
            {
                throw $this->createNotFoundException("Cette redirection n'existe pas!");
            }

            $listing = $this->getDoctrine()->getManager()->getRepository('OSELInfoBundle:RedirectionMail')->findAll();
            $redirection = $this->getDoctrine()->getManager()->getRepository('OSELInfoBundle:RedirectionMail')->findOneBy(array('id' => $id));;
            $form = $this->get('form.factory')->create(RedirectionMailType::class, $redirection);

            if ($form->handleRequest($request)->isSubmitted())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($redirection);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'La redirection a bien été modifié');

                return $this->redirect($this->generateUrl('osel_info_redirections_gestion'));
            }
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');

            return $this->redirect($this->generateUrl('osel_core_home'));
        }


        return $this->get('templating')->renderResponse('OSELInfoBundle:Info:redirections_gestion.html.twig',array(
            'selectedPage'  => 'infos',
            'form'          => $form->createView(),
            'mails'         => $listing));
    }

    public function deleteAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            if($id < 1)
            {
                throw $this->createNotFoundException("Cette redirection n'existe pas!");
            }

            $redirection = $this->getDoctrine()->getManager()->getRepository('OSELInfoBundle:RedirectionMail')->findOneBy(array('id' => $id));;

                $em = $this->getDoctrine()->getManager();
                $em->remove($redirection);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'La redirection a bien été supprimé');

                return $this->redirect($this->generateUrl('osel_info_redirections_gestion'));
        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');

            return $this->redirect($this->generateUrl('osel_core_home'));
        }
    }
}