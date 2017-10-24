<?php

namespace OSEL\MusicsheetBundle\Controller;

use OSEL\MusicsheetBundle\Entity\Musicsheet;
use OSEL\MusicsheetBundle\Entity\Composer;
use OSEL\MusicsheetBundle\Entity\SheetType;
use OSEL\MusicsheetBundle\Entity\Instrument;
use OSEL\MusicsheetBundle\Form\InstrumentType;
use OSEL\MusicsheetBundle\Form\MusicsheetType;
use OSEL\MusicsheetBundle\Form\MusicsheetCompleteType;
use OSEL\MusicsheetBundle\Form\ComposerType;
use OSEL\MusicsheetBundle\Form\SheetTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MusicsheetController extends Controller
{
    /**
     * @param $idComposer
     * @param $idMusicsheet
     * @param $idPart
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function gestionAction($idComposer, $idMusicsheet, $idPart, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTITION'))
        {
            $composers  = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->myFindAll();
            $musicsheet     = new Musicsheet();
            $composer       = new Composer();
            $type           = new SheetType();
            $instrument     = new Instrument();

            //Gestion du formulaire pour ajouter une partition
            $musicsheetForm = $this->get('form.factory')->create(MusicsheetType::class, $musicsheet);

            if($request->isMethod('POST') && $musicsheetForm->handleRequest($request)->isValid())
            {

                $em = $this->getDoctrine()->getManager();

                $musicsheet->upload();
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                {
                    $musicsheet->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }

                $em->persist($musicsheet);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'La partition a bien été rajoutée');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }


            //Gestion du formulaire pour ajouter un Compositeur
            $composerForm = $this->get('form.factory')->create(ComposerType::class, $composer);

            if($request->isMethod('POST') && $composerForm->handleRequest($request)->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                if($composer->setComposer($composer->getLastName() . ' ' . $composer->getName()))
                {
                    $directory = $this->get('kernel')->getRootDir() . '/../web/bundles/oselmusicsheet/index/' . $composer->getComposer();
                    if(!is_dir($directory))
                    {
                        if(mkdir($directory))
                        {
                            $request->getSession()->getFlashBag()->add('notice', 'Le dossier a bien été crée.');

                            if(chmod($directory, 0777))
                            {
                                $request->getSession()->getFlashBag()->add('notice', 'Les droits du dossier ont bien été modifié.');
                            }
                        }
                    }
                    else
                    {
                        $request->getSession()->getFlashBag()->add('ERROR', 'Le dossier existait déjà.');
                    }

                    $em->persist($composer);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Le compositeur a bien été bien ajouté.');

                    return $this->redirectToRoute('osel_musicsheet_gestion');
                }
                else
                {
                    $request->getSession()->getFlashBag()->add('notice', 'Le compositeur existe déjà ou une autre erreur est survenue');

                    return $this->redirectToRoute('osel_musicsheet_gestion');
                }

            }



            //Gestion du formulaire pour ajouter un type de partition
            $typeForm = $this->get('form.factory')->create(SheetTypeType::class, $type);

            if($request->isMethod('POST') && $typeForm->handleRequest($request)->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Type bien ajouté.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }

            //Gestion du formulaire pour ajouter un instrument
            $instrumentForm = $this->get('form.factory')->create(InstrumentType::class, $instrument);

            if($request->isMethod('POST') && $instrumentForm->handleRequest($request)->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($instrument);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'L\'instrument a bien été ajouté.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }


            return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:gestion.html.twig', array(
				'composers'			=> $composers,
                'musicsheetForm'    => $musicsheetForm->createView(),
                'composerForm'      => $composerForm->createView(),
                'typeForm'          => $typeForm->createView(),
                'instrumentForm'    => $instrumentForm->createView(),
				'selectedPage'		=> 'partition'
            ));
        }
    }

    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') OR !$this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            {
                $instruments = array();
                $userInstruments = $this->container->get('security.token_storage')->getToken()->getUser()->getInstrumentMusicsheets();
                foreach ($userInstruments as $instr)
                {
                    array_push($instruments, $instr->getId());
                }

                //$parts = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Parts')->findInstrumentParts($instruments);
                $parts = null;

            }
            else{
                $parts = null;
            }


            $composers = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->findActive();
        }
        return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:index.html.twig', array(
            'composers'			=> $composers,
            'parts'             => $parts,
            'selectedPage'		=> 'partition'
        ));
    }

    public function viewAction($id)
    {
    	if($id < 1)
    	{
    		throw new NotFoundHttpException('Page inexistante.');
    	}
    	$user = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Musicsheet')->findOneBy(array('id' => $id));

    	return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:view.html.twig', array(
        	'user' => $user));
    }

    public function viewPartAction($id)
    {
        if($id < 1)
        {
            throw new NotFoundHttpException('Page inexistante.');
        }
        $user = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Part')->findOneBy(array('id' => $id));

        return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:viewPart.html.twig', array(
            'user' => $user));
    }

    public function modifyAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Page inexistante.');
            }
            else
            {
                $musicsheet = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Musicsheet')->find($id);

                $form = $this->get('form.factory')->create(MusicsheetType::class, $musicsheet, array('action' => $this->generateUrl('osel_musicsheet_modify', array('id' => $id))));

                if ($form->handleRequest($request)->isValid())
                {
                    $em = $this->getDoctrine()->getManager();

                    $musicsheet->upload();

                    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        $musicsheet->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                    }

                    $em->persist($musicsheet);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'La partition a bien été modifiée');

                    return $this->redirectToRoute('osel_musicsheet_gestion');
                }
            }

        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->get('templating')->renderResponse('OSELCoreBundle:Core:index.html.twig', array(
                'selectedPage' => 'home'));
        }

        $page = $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:modify.html.twig', array(
            'musicsheet'    => $musicsheet,
            'musicsheetForm'=> $form->createView(),
            'countParts'    => $musicsheet->getParts()->count(),
            'selectedPage'  => 'partition'));

        /*if($request->isXmlHttpRequest())
        {
            $response = new Response($page);
            $response->headers->set('Content-Type', 'application/html');

            return $response;
        }*/

        return $page;
    }

    public function modifyCompleteAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Page inexistante.');
            }
            else
            {
                $musicsheet = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Musicsheet')->find($id);

                $form = $this->get('form.factory')->create(MusicsheetCompleteType::class, $musicsheet);

                if ($form->handleRequest($request)->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($musicsheet);
                    $em->flush();

                    if($request->isXmlHttpRequest())
                    {
                        $json = json_encode(array(
                            'id'    => $musicsheet->getId(),
                            'actif' => $musicsheet->getActif()
                        ));

                        $response = new Response($json);
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    }

                    $request->getSession()->getFlashBag()->add('notice', 'le partition a bien changé de statut');

                    return $this->redirect($this->generateUrl('osel_musicsheet_gestion'));
                }
            }

        }
        else
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->get('templating')->renderResponse('OSELCoreBundle:Core:index.html.twig', array(
                'selectedPage' => 'home'));
        }

        return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:complete.html.twig', array(
            'musicsheet'    => $musicsheet,
            'form'          => $form->createView(),
            'selectedPage'  => 'partition'));
    }

    public function getComposersAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {

                $composers = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->findAll();
                $html = "";
                foreach ($composers as $composer) {
                    $route = $this->generateUrl('osel_musicsheet_get_musicsheets', array('id' => $composer->getId()));

                    $html .= "<li class=\"list-group-item composerButton\" href=\"" . $route . "\"><span>" . $composer->getComposer() . "</span><i class=\"glyphicon glyphicon-triangle-right\" style=\"float:right;\"></i></li>";
                }

                $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb.js\"></script>";

                $response = new Response($html);
                $response->headers->set('Content-Type', 'application/html');

                return $response;
            } else {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les compositeurs.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function getComposersUserAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {

                $composers = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->findActive();
                $html = "";
                foreach ($composers as $composer) {
                    $route = $this->generateUrl('osel_musicsheet_get_musicsheets_user', array('id' => $composer->getId()));

                    $html .= "<li class=\"list-group-item composerButton\" href=\"" . $route . "\"><span>" . $composer->getComposer() . "</span><i class=\"glyphicon glyphicon-triangle-right\" style=\"float:right;\"></i></li>";
                }

                $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb_user.js\"></script>";

                $response = new Response($html);
                $response->headers->set('Content-Type', 'application/html');

                return $response;
            } else {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les compositeurs.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function getMusicsheetsAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {
                if ($id > 0) {
                    $partitions = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Musicsheet')->getMusicsheetsByComposer($id);
                    $html = "";
                    foreach ($partitions as $partition) {

                        $route = $this->generateUrl('osel_musicsheet_get_parts', array('id' => $partition->getId()));

                        $html .= "<li class=\"list-group-item musicsheetButton\" href=\"" . $route . "\" data=\"" . $partition->getId() . "\" data-composer=\"" . $partition->getComposer()->getId() . "\"><span id=\"musicsheet-complete-" . $partition->getId() ."\" class='state-icon glyphicon";

                        if($partition->getActif())
                        {
                            $html .= " glyphicon-check'";
                        }
                        else
                        {
                            $html .= " glyphicon-unchecked'";
                        }
                        $html .= "></span>" . " " . $partition->getTitle() . "<i class=\"glyphicon glyphicon-triangle-right\" style=\"float:right;\"></i></li>";
                    }

                    $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb.js\"></script>";

                    $response = new Response($html);
                    $response->headers->set('Content-Type', 'application/html');

                    return $response;
                }
            }
            else
            {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les morceaux de ce compositeur.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function getMusicsheetsUserAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {
                if ($id > 0) {
                    $partitions = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Musicsheet')->getMusicsheetsByComposerActive($id);
                    $html = "";
                    foreach ($partitions as $partition) {

                        $route = $this->generateUrl('osel_musicsheet_get_parts_user', array('id' => $partition->getId()));

                        $html .= "<li class=\"list-group-item musicsheetButton\" href=\"" . $route . "\" data=\"" . $partition->getId() . "\" data-composer=\"" . $partition->getComposer()->getId() . "\">" . $partition->getTitle() . "<i class=\"glyphicon glyphicon-triangle-right\" style=\"float:right;\"></i></li>";
                    }

                    $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb_user.js\"></script>";

                    $response = new Response($html);
                    $response->headers->set('Content-Type', 'application/html');

                    return $response;
                }
            }
            else
            {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les morceaux de ce compositeur.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function getPartsAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {
                if ($id > 0) {
                    $parts = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Parts')->getPartsByMusicsheet($id);
                    $html = "";
                    foreach ($parts as $part) {
                        $mobileDetector = $this->get('mobile_detect.mobile_detector');

                        if($mobileDetector->isMobile() || $mobileDetector->isTablet()){
                            $route = $this->container->get('assets.packages')->getUrl($part->getUrl());
                        }
                        else{
                            $route = $this->generateUrl('osel_musicsheet_download_part', array('id' => $part->getId()));
                        }

                        $html .= "<li class=\"list-group-item partButton\" href=\"" . $route . "\" data=\"" . $part->getMusicsheet()->getId() . "\"><span>" . $part->getTitle() . "</span></li>";
                    }

                    $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb.js\"></script>";

                    $response = new Response($html);
                    $response->headers->set('Content-Type', 'application/html');

                    return $response;
                }
            }
            else
            {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les morceaux de ce compositeur.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function getPartsUserAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if ($request->isXMLHttpRequest()) {
                if ($id > 0) {
                    $parts = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Parts')->getPartsByMusicsheet($id);
                    $html = "";
                    foreach ($parts as $part) {
                        $mobileDetector = $this->get('mobile_detect.mobile_detector');

                        if($mobileDetector->isMobile() || $mobileDetector->isTablet()){
                            $route = $this->container->get('assets.packages')->getUrl($part->getUrl());
                        }
                        else{
                            $route = $this->generateUrl('osel_musicsheet_download_part', array('id' => $part->getId()));
                        }


                        $html .= "<li class=\"list-group-item partButton\" href=\"" . $route . "\" data=\"" . $part->getMusicsheet()->getId() . "\"><span>" . $part->getTitle() . "</span></li>";
                    }

                    $html .= "<script src=\"/bundles/oselmusicsheet/js/musicsheets_arb_user.js\"></script>";

                    $response = new Response($html);
                    $response->headers->set('Content-Type', 'application/html');

                    return $response;
                }
            }
            else
            {
                $request->getSession()->getFlashBag()->add('ERROR', 'Nous n\'avons pas pu récuperer les morceaux de ce compositeur.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }
        }
    }

    public function downloadPartAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Partition inexistante.');
            }

            $part = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Parts')->find($id);

            $path = $this->get('kernel')->getProjectDir() . "/web/" . $part->getUrl();
            $content = file_get_contents($path);

            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$part->getTitle());

            $response->setContent($content);

            return $response;
        }
    }
}
