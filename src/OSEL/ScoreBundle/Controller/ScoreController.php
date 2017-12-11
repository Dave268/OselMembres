<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 06.11.17
 * Time: 16:49
 */

namespace OSEL\ScoreBundle\Controller;


use OSEL\ScoreBundle\Entity\BioComposer;
use OSEL\ScoreBundle\Entity\Composer;
use OSEL\ScoreBundle\Entity\ImgComposer;
use OSEL\ScoreBundle\Entity\Parts;
use OSEL\ScoreBundle\Entity\Score;
use OSEL\ScoreBundle\Form\ActivatePartType;
use OSEL\ScoreBundle\Form\ActivateScoreType;
use OSEL\ScoreBundle\Form\BioComposerType;
use OSEL\ScoreBundle\Form\ComposerType;
use OSEL\ScoreBundle\Form\ImgComposerType;
use OSEL\ScoreBundle\Form\PartsType;
use OSEL\ScoreBundle\Form\ScoreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ScoreController extends Controller
{
    private function is_dir_empty($dir) {
        if (!is_readable($dir)) return NULL;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return FALSE;
            }
        }
        return TRUE;
    }

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

    public function createScoreAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        if($id <= 0){
            $score = new Score();
        }
        else{
            $score = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->find($id);
        }

        $scoreForm = $this->createForm(ScoreType::class, $score);;

        if($request->isMethod('POST') && $scoreForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if($score->getId() === null)
                {
                    $score->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                else{
                    $score->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
            }

            $root = $this->container->getParameter('kernel.project_dir') . "/web/";
            $score->setPath("library/" . $score->getComposer()->getComposer() . "/" . $score->getTitle());

            if(!is_dir($root . $score->getPath()))
            {
                mkdir($root . $score->getPath());
            }
            if($score->getId() !== null){
                $parts=$this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getPartsByScore($score->getId());
                foreach ($parts as $part){
                    if($part->getPath() !== $score->getPath()){
                        $oldPath = $root . $part->getPath();
                        rename( $oldPath ."/" . $part->getName(), $root . $score->getPath() ."/" . $part->getName());
                        $request->getSession()->getFlashBag()->add('success', 'Le fichier suivant a été déplacé: ' . $part->getOriginalName());
                        if ($this->is_dir_empty($oldPath)) {
                            rmdir($oldPath);
                            $request->getSession()->getFlashBag()->add('success', 'L\'ancien dossier a été supprimé: ' . $part->getPath());
                        }
                        $part->setPath($score->getPath());
                        $em->persist($part);
                    }
                }
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

    public function addComposerAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        if($id <= 0){
            $composer = new Composer();
        }
        else{
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($id);
        }

        $composerForm = $this->createForm(ComposerType::class, $composer);;

        if($request->isMethod('POST') && $composerForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if($composer->getId() === null)
                {
                    $composer->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                else{
                    $composer->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
            }

            $root = $this->container->getParameter('kernel.project_dir') . "/web/library/" . $composer->getLastName() . " " . $composer->getName();

            if(!is_dir($root) && $composer->getId() === null)
            {
                mkdir($root);
            }
            elseif (!is_dir($root) && $composer->getId() !== null)
            {
                $oldroot    = $this->container->getParameter('kernel.project_dir') . "/web/library/" . $composer->getComposer();
                rename($oldroot, $root);
                $scores = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->getScoresByComposer($composer->getId());

                foreach ($scores as $score){
                    $parts=$this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getPartsByScore($score->getId());
                    $score->setPath($root. "/" . $score->getTitle());
                    $em->persist($score);
                    foreach ($parts as $part){
                        $part->setPath($score->getPath());
                        $em->persist($part);
                    }
                }

            }

            $composer->setComposer($composer->getLastName() . " " . $composer->getName());


            if($composer->getId() !== null)
            {
                $request->getSession()->getFlashBag()->add('success', 'La compositeur a bien été modifié');
            }
            else{
                $request->getSession()->getFlashBag()->add('success', 'La compositeur a bien été ajouté');
            }
            $em->persist($composer);
            $em->flush();



            return $this->redirectToRoute('osel_score_gestion', array(
                'letter' => substr($composer->getComposer(), 0, 1)
        ));

        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:composerForm.html.twig', array(
            'form'    => $composerForm->createView()
        ));

    }

    public function uploadPartsAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }
        $parts = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getPartsByScore($id);
        $letter = substr($this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->find($id)->getComposer()->getComposer(), 0, 1);

        return $this->get('templating')->renderResponse('ScoreBundle:score:uploadForm.html.twig', array(
            'score'     => $id,
            'parts'     => $parts,
            'letter'    => $letter
        ));

    }

    public function indexAction($idComposer, $idScore, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $composers = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->findActiveComposers();

        $scores = null;
        $score = null;
        $composer = null;
        $parts = null;

        if ($idComposer > 0)
        {
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($idComposer);
            if($idScore > 0) {
                $parts = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getActivePartsByScore($idScore);
                $score = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->find($idScore);
            }
            elseif ($idScore === 0){
                $scores = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->getScoresByComposerActive($idComposer);
            }
        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:index.html.twig', array(
            "composers" => $composers,
            "composer"  => $composer,
            "scores"    => $scores,
            "score"     => $score,
            "parts"     => $parts,
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

    public function searchAction($text, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('osel_score_gestion');
        }

        $composers = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->findSearch($text);
        $em = $this->getDoctrine()->getManager();
        $object = array();

        foreach ($composers as $composer)
        {
            array_push($object, array("id"=>$composer->getId(), 'composer' =>$composer->getComposer(), 'scores' => $composer->getNbScores(), 'actif' => $composer->getActif()));
        }

        $json = json_encode($object);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function deletePartAction($id, Request $request)
    {
            $part = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->find($id);
            $em = $this->getDoctrine()->getManager();
            $path = $this->container->getParameter('kernel.project_dir') . "/web/" . $part->getPath() . "/" . $part->getName();

            unlink($path);
            $em->remove($part);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La partition suivante a été supprimé' . $part->getOriginalName());

            if($request->isXmlHttpRequest()) {
                $json = json_encode(array(
                    'id'    => $part->getId(),
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $referer = $request->headers->get('referer');;

        return new RedirectResponse($referer);

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

    public function viewPartAction($id, Request $request)
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

            $response = new BinaryFileResponse($path);


            return $response;
        }
    }

    public function activatePartAction($id, Request $request)
    {
        $redirect = new RedirectResponse($request->headers->get('referer'));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            return $redirect;
        }

        $part = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->find($id);

        $form = $this->get('form.factory')->create(ActivatePartType::class, $part);

            if ($form->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($part);
                $em->flush();

                if($request->isXmlHttpRequest())
                {
                    $json = json_encode(array(
                        'id'    => $part->getId(),
                        'actif' => $part->getActif(),
                    ));

                    $response = new Response($json);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }

                    $request->getSession()->getFlashBag()->add('success', 'La partition a bient été modifié');

                return $redirect;
            }

        return $this->render('ScoreBundle:score:activate.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function activateScoreAction($id, Request $request)
    {
        $redirect = new RedirectResponse($request->headers->get('referer'));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            return $redirect;
        }

        $score = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Score')->find($id);

        $form = $this->get('form.factory')->create(ActivateScoreType::class, $score);

        if ($form->handleRequest($request)->isValid()) {
            $parts = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->getPartsByScore($score->getId());
            $em = $this->getDoctrine()->getManager();

            if($score->getActif())
            {
                foreach ($parts as $part){
                    $part->setActif(true);
                    $em->persist($part);
                }
            }
            else
            {
                foreach ($parts as $part){
                    $part->setActif(false);
                    $em->persist($part);
                }
            }

            $em->persist($score);
            $em->flush();

            if($request->isXmlHttpRequest())
            {
                $json = json_encode(array(
                    'id'    => $score->getId(),
                    'actif' => $score->getActif(),
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $request->getSession()->getFlashBag()->add('success', 'Le morceau et toutes ces partitions ont bien été modifié');

            return $redirect;
        }

        return $this->render('ScoreBundle:score:activate.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function modifyPartAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette page');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }

        $part =$this->getDoctrine()->getManager()->getRepository('ScoreBundle:Parts')->find($id);
        $form = $this->get('form.factory')->create(PartsType::class, $part);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($part);
            $em->flush();

            if($request->isXmlHttpRequest()) {

                $json = json_encode(array(
                    'id' => $part->getId(),
                    'name'  => $part->getOriginalName()
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            $request->getSession()->getFlashBag()->add('success', 'Le partition a bien été modifié');

            return $this->redirect($this->generateUrl('osel_score_gestion', array(
                "letter"        => 'a',
                'idComposer'    => $part->getScore()->getComposer()->getId(),
                'idScore'       => $part->getScore()->getId()
            )));

        }


        return $this->render('ScoreBundle:score:partForm.html.twig', array(
            'form'         => $form->createView(),
        ));
    }

    public function viewComposerAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_CA')) {
        }
            if ($id < 1) {
                throw new NotFoundHttpException('Page inexistante.');
            }
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($id);

            return $this->get('templating')->renderResponse('ScoreBundle:composer:view.html.twig', array(
                'composer' => $composer));
    }

    public function addComposerBioAction($idcomposer, $id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        if($id <= 0){
            $bio = new BioComposer();
        }
        else{
            $bio = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:BioComposer')->find($id);
        }

        $bioForm = $this->createForm(BioComposerType::class, $bio);

        if($request->isMethod('POST') && $bioForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($idcomposer);
            $bio->setComposer($composer);

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if($bio->getId() === null)
                {
                    $bio->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                else{
                    $bio->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
            }


            if($bio->getId() !== null)
            {
                $request->getSession()->getFlashBag()->add('success', 'Le texte a bien été modifié');
            }
            else{
                $request->getSession()->getFlashBag()->add('success', 'Le texte a bien été ajouté');
            }
            $em->persist($bio);
            $em->flush();



            return $this->redirectToRoute('osel_score_view_composer', array(
                'id' => $idcomposer
            ));

        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:bioForm.html.twig', array(
            'form'    => $bioForm->createView()
        ));

    }

    public function addComposerImgAction($idcomposer, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')){
            return $this->redirectToRoute('osel_core_home');
        }

        $image = new ImgComposer();
        $imageForm = $this->createForm(ImgComposerType::class, $image);

        if($request->isMethod('POST') && $imageForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $composer = $this->getDoctrine()->getManager()->getRepository('ScoreBundle:Composer')->find($idcomposer);
            $image->setComposer($composer);
            $image->uploadImage();

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if($image->getId() === null)
                {
                    $image->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                else{
                    $image->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
            }

            if($image->getId() !== null)
            {
                $request->getSession()->getFlashBag()->add('success', 'L\'image a bien été modifié');
            }
            else{
                $request->getSession()->getFlashBag()->add('success', 'L\'image a bien été ajouté');
            }
            $em->persist($image);
            $em->flush();



            return $this->redirectToRoute('osel_score_view_composer', array(
                'id' => $idcomposer
            ));

        }

        return $this->get('templating')->renderResponse('ScoreBundle:score:imgform.html.twig', array(
            'form'    => $imageForm->createView()
        ));

    }

}