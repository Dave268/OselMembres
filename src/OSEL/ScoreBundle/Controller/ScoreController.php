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
    public function gestionAction($letter, $idComposer, $idScore, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $composers = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->findComposers($letter);

        $scores = null;
        $score = null;
        $composer = null;
        $parts = null;

        if ($idComposer > 0)
        {
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($idComposer);
            if($idScore > 0) {
                $parts = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getPartsByScore($idScore);
                $score = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->find($idScore);
            }
            elseif ($idScore === 0){
                $scores = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->getScoresByComposer($idComposer);
            }
        }




        return $this->get('templating')->renderResponse('ScoreBundle:score:gestion.html.twig', array(
            "composers" => $composers,
            "composer"  => $composer,
            "scores"    => $scores,
            "score"     => $score,
            "parts"     => $parts,
            "letter"    => strtolower($letter)
        ));
    }

    public function createScoreAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        $score = new Score();

        $scoreForm = $this->createForm(ScoreType::class, $score);;

        if($request->isMethod('POST') && $scoreForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $score->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            }

            $root = $this->container->getParameter('kernel.project_dir') . "/web/";
            $score->setPath("library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle());

            if(!is_dir($root . $score->getPath()))
            {
                mkdir($root . $score->getPath());
            }

            $em->persist($score);
            $em->flush();

            //$request->getSession()->getFlashBag()->add('success', 'La partition a bien été crée');

            return $this->redirectToRoute('osel_score_upload_parts', array(
                'id' => $score->getId()
            ));
        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:scoreForm.html.twig', array(
            'form'    => $scoreForm->createView()
        ));

    }

    public function uploadPartsAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:uploadForm.html.twig', array(
            'score'    => $id
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

    public function uploadAjaxAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('osel_score_gestion');
        }

        $score = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->findOneBy(array('id' => $id));
        $files = $request->files->get('upload');
        $fileUpload = $this->container->get('score.uploader');
        $em = $this->getDoctrine()->getManager();

        foreach ($files as $file)
        {
            $part = new Parts();
            $uniq = md5(uniqid()).'.'. $file->guessExtension();
            $part->setName($uniq);
            $part->setOriginalName($file->getClientOriginalName());
            $part->setPath($score->getPath());
            $part->setScore($score);
            $fileUpload->upload($file, $part->getPath(), $part->getName());
            $em->persist($part);
            $request->getSession()->getFlashBag()->add('success', 'La partition '. $part->getOriginalName() . ' a été uploadé sur le serveur!');
        }
        $em->flush();
        $json = json_encode(array(
            'id'    => $part->getId()
        ));

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function deletePartAction($id, Request $request)
    {
            $part = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->find($id);
            $em = $this->getDoctrine()->getManager();
            $path = $this->container->getParameter('kernel.project_dir') . "/web/" . $part->getUrl();

            unlink($path);
            $em->remove($part);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La partition a été supprimé');

            if($request->isXmlHttpRequest()) {
                $json = json_encode(array(
                    'id'    => $part->getId(),
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            return $this->redirect($this->generateUrl('osel_event_list'));

    }

    public function downloadPartAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Partition inexistante.');
            }

            $part = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->findOneBy(array('id' => $id));

            $path = $this->get('kernel')->getProjectDir() . "/web/" . $part->getPath() . "/" . $part->getName();
            $content = file_get_contents($path);

            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$part->getOriginalName());

            $response->setContent($content);

            return $response;
        }
    }

    /*
    public function createScoreAction(Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        $score = new Score();
        $form = $request->request->get('osel_scorebundle_score');
        $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->findOneBy(array('id' => $form['composer']));
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $score->setUser($this->container->get('security.token_storage')->getToken()->getUser());
        }
        $score->setTitle($form['title']);
        $score->setYear($form['year']);
        $score->setComposer($composer);
        $root = $this->container->getParameter('kernel.project_dir') . "/web/";
        $score->setPath("library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle());

        if(!is_dir($root . $score->getPath()))
        {
            mkdir($root . $score->getPath());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($score);
        $em->flush();

        $request->getSession()->getFlashBag()->add('Error', 'Le morceau '. $score->getTitle() . 'a été crée');

        if($request->isXmlHttpRequest()) {
            $json = json_encode(array(
                'id'    => $score->getId(),
            ));

            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return $this->redirect($this->generateUrl('osel_event_list'));
    }
    */
}