<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 06.11.17
 * Time: 16:49
 */

namespace OSEL\ScoreBundle\Controller;


use OSEL\ScoreBundle\Entity\Score;
use OSEL\ScoreBundle\Form\ScoreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ScoreController extends Controller
{
    public function gestionAction($idComposer, $idScore, $idPart, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $score = new Score();

        //Gestion du formulaire pour ajouter une partition
        $scoreForm = $this->get('form.factory')->create(ScoreType::class, $score);

        if($request->isMethod('POST') && $scoreForm->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $score->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            }

            $em->persist($score);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La partition a bien été rajoutée');

            return $this->redirectToRoute('osel_score_gestion');
        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:gestion.html.twig', array(
            'form'    => $scoreForm->createView()
        ));
    }


    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            $composers = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->findActive();
        }
        return $this->get('templating')->renderResponse('OSELScoreBundle:score:index.html.twig', array(
            'composers'			=> $composers,
            'parts'             => $parts,
            'selectedPage'		=> 'partition'
        ));
    }

}