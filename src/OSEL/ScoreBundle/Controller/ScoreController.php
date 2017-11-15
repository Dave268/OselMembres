<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 06.11.17
 * Time: 16:49
 */

namespace OSEL\ScoreBundle\Controller;


use OSEL\ScoreBundle\Entity\Parts;
use OSEL\ScoreBundle\Entity\Score;
use OSEL\ScoreBundle\Form\ScoreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
            $parts = $this->getDoctrine()->getManager()->getRepository(Parts::class)->findBy(array('status' => "temp"));
            $em = $this->getDoctrine()->getManager();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $score->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            }
            $root = $this->container->getParameter('kernel.project_dir') . "/web/";
            if(!is_dir($root . "library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle()))
            {
                mkdir($root . "library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle());
            }
            foreach ($parts as $part)
            {
                $part->setScore($score);
                $part->setStatus("published");
                $url = "library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle() . "/" . $part->getName();
                rename($root . $part->getUrl(), $root . $url);
                $part->setUrl($url);
                $part->increase();
                $em->persist($part);
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
            'selectedPage'		=> 'partition'
        ));
    }

    public function uploadAjaxAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('osel_score_gestion');
        }

        $files = $request->files->get('upload');
        $fileUpload = $this->container->get('score.uploader');
        $em = $this->getDoctrine()->getManager();

        foreach ($files as $file)
        {
            $part = new Parts();
            $uniq = md5(uniqid()).'.'. $file->guessExtension();
            $part->setName($uniq);
            $part->setOriginalName($file->getClientOriginalName());
            $part->setUrl("library/temp/" . $uniq);
            $fileUpload->upload($file, "library/temp", $uniq);
            $em->persist($part);
            $json = json_encode(array(
                'id'    => $part->getId(),
            ));
        }
        $em->flush();

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function deletePartAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_WEBMASTER'))
        {
            $part = $this->getDoctrine()->getManager()->getRepository('OSELScoreBundle:Part')->findOneBy(array('id' => $id));
            $em = $this->getDoctrine()->getManager();
            $path = $this->container->getParameter('kernel.project_dir') . "/web/" . $part->getUrl();

            unlink($path);

            $em->remove($part);
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

}