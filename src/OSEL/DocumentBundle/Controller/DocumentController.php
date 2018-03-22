<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12.02.18
 * Time: 19:42
 */

namespace OSEL\DocumentBundle\Controller;

use OSEL\DocumentBundle\Entity\Directory;
use OSEL\DocumentBundle\Entity\File;
use OSEL\DocumentBundle\Form\ActivateDirType;
use OSEL\DocumentBundle\Form\ActivateFileType;
use OSEL\DocumentBundle\Form\DirectoryType;
use OSEL\DocumentBundle\Form\FileModifyType;
use OSEL\DocumentBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocumentController extends Controller
{
    private function folderToZip($folder, &$zipFile, $exclusiveLength) {
        //$myfile = fopen($this->get('kernel')->getProjectDir() . "/web/files/temp/newfile.txt", "w");
        $handle = opendir($folder);
        $foldertemp = substr($folder, $exclusiveLength);
        //fwrite($myfile, "folder:" .$folder . "\n");
        //fwrite($myfile, "foldertemp:" .$foldertemp . "\n");
        $split = explode("/", $foldertemp);
        $path = '';
        for($i = 0; $i < count($split); $i++)
        {
            $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findOneBy(array('name' => $split[$i]));
            if($dir != null)
            {
                $path .= "/" . $dir->getOriginalName();
            }
        }

        //fwrite($myfile, "path:" .$path . "\n");
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                //$localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findOneBy(array('name' => $f))->getOriginalName();
                    $zipFile->addFile($filePath, $path . "/" . $file);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($path);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        //fclose($myfile);
        closedir($handle);
    }

    public function searchAction($text, $id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CA')){
            return $this->redirectToRoute('osel_core_home');
        }
        if(!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute('osel_documents_index');
        }

        if($text == '%%%')
        {
            $dirs = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findByDir($id, 'originalName', 'ASC');
            $files = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findByDir($id, 'originalName', 'ASC');
        }
        else
        {
            $folder = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($id);
            $dirs = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findSearch($text, $folder->getPath() . "/" . $folder->getName());
            $files = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findSearch($text, $folder->getPath() . "/" . $folder->getName());
        }

        $object = array();

        foreach ($dirs as $dir)
        {
            array_push($object, array("id"=>$dir->getId(), 'originalName' => $dir->getOriginalName(), 'dateAdd' => $dir->getDateAdd()->format('d/m/Y h:i'), 'dateModified' => $dir->getDateUpdate()->format('d/m/Y h:i'), 'enabled' => $dir->getEnabled(), 'role' => $dir->getRole()->getName(), 'isdir' => $dir->getType(), 'icon' => 'glyphicon glyphicon-folder-open'));
        }

        foreach ($files as $file)
        {
            array_push($object, array("id"=>$file->getId(), 'originalName' => $file->getOriginalName(), 'dateAdd' => $file->getDateAdd()->format('d/m/Y h:i'), 'dateModified' => $file->getDateUpdate()->format('d/m/Y h:i'), 'enabled' => $file->getEnabled(), 'role' => $file->getRole()->getName(), 'isdir' => $file->getType(), 'icon' => $file->getIcon()));
        }

        $json = json_encode($object);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function indexAction($idDir, $criteria, $order, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CA')){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        if($idDir < 0) {
            $request->getSession()->getFlashBag()->add('ERROR', 'Une erreur est survenue dans l\'url de la page');
            return $this->redirectToRoute('osel_core_home');
        }

        $directories = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findByDir($idDir, $criteria, $order);
        $files = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findByDir($idDir, $criteria, $order);

        if($idDir == 0)
        {
            $directory = null;
            $path = null;
        }
        else
        {
            $directory = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($idDir);
            $idup = $directory->getIdDir();
            $path = array($directory);

            for($i = 1; $i < $directory->getRank(); $i++)
            {
                $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($idup);
                $idup = $dir->getIdDir();
                array_unshift($path, $dir);
            }

        }


        return $this->render('DocumentBundle:documents:index.html.twig', array(
            'directories'   => $directories,
            'files'         => $files,
            'idDir'         => $idDir,
            'criteria'      => $criteria,
            'order'         => $order,
            'directory'     => $directory,
            'path'          => $path
        ));
    }

    public function addFileAction($idDir, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CA')){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($idDir);

        if($dir != null && !$this->get('security.authorization_checker')->isGranted($dir->getRole()->getRole())){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous ne pouvez pas ajouter un fichier dans ce dossier');
            return $this->redirectToRoute('osel_documents_index');
        }

        if($dir == null){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous ne pouvez pas ajouter de fichiers dans le dossier racine. Vous devez d\'abord créer un dossier ou vous placer dans un dossier');
            return $this->redirectToRoute('osel_documents_index');
        }

        $file = new File();
        $fileForm = $this->createForm(FileType::class, $file);

        if($request->isMethod('POST') && $fileForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file->setDirectory($dir);
            if($file->upload($dir->getPath()))
            {
                $request->getSession()->getFlashBag()->add('success', 'Le fichier a été correctement uploadé');
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    if($file->getId() === null)
                    {
                        $file->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                    }
                    else{
                        $file->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                    }
                }

                if($file->getId() !== null)
                {
                    $request->getSession()->getFlashBag()->add('success', 'Le fichier a bien été modifié');
                }
                else{
                    $request->getSession()->getFlashBag()->add('success', 'Le fichier a bien été ajouté');
                }
                $em->persist($file);
                $em->flush();

            }
            else{
                $request->getSession()->getFlashBag()->add('ERROR', 'Une erreur s\'est produite');
            }

            return $this->redirectToRoute('osel_documents_index', array(
                'idDir' => $idDir
            ));

        }

        return $this->get('templating')->renderResponse('DocumentBundle:files:form.html.twig', array(
            'form'      => $fileForm->createView(),
            'idDir'     => $idDir
        ));

    }

    public function modifyFileAction($id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CA')){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->find($id);

        if($file != null && !$file->getEnabled()){
            $request->getSession()->getFlashBag()->add('ERROR', 'Ce fichier est verrouillé');
            return $this->redirectToRoute('osel_documents_index', array(
                'idDir' => $file->getDirectory()->getId()));
        }

        if($file != null && !$this->get('security.authorization_checker')->isGranted($file->getRole()->getRole())){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous ne pouvez pas modifier ce fichier');
            return $this->redirectToRoute('osel_documents_index');
        }

        if($file == null){
            $request->getSession()->getFlashBag()->add('ERROR', 'Ce fichier n\'existe pas');
            return $this->redirectToRoute('osel_documents_index');
        }

        $fileForm = $this->createForm(FileModifyType::class, $file);

        if($request->isMethod('POST') && $fileForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                        $file->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                    $request->getSession()->getFlashBag()->add('success', 'Le fichier a bien été modifié');

                $em->persist($file);
                $em->flush();

            return $this->redirectToRoute('osel_documents_index', array(
                'idDir' => $file->getDirectory()->getId(),
            ));

        }

        return $this->get('templating')->renderResponse('DocumentBundle:files:modifyForm.html.twig', array(
            'form'      => $fileForm->createView(),
            'id'     => $file->getId()
        ));

    }

    public function activateFileAction($id, Request $request)
    {
        $redirect = new RedirectResponse($request->headers->get('referer'));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')) {
            return $redirect;
        }

        $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->find($id);

        $form = $this->get('form.factory')->create(ActivateFileType::class, $file);

        if ($form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            if($request->isXmlHttpRequest())
            {
                $json = json_encode(array(
                    'id'    => $file->getId(),
                    'enabled' => $file->getEnabled(),
                    'data'  => 'file',
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            if($file->getEnabled())
            {
                $request->getSession()->getFlashBag()->add('success', 'Le fichier a bien été déverouillé');
            }
            else
            {
                $request->getSession()->getFlashBag()->add('success', 'Le fichier a bien été verouillé');
            }

            return $redirect;
        }

        return $this->render('DocumentBundle:documents:activate.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function downloadFileAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Fichier inexistant.');
            }

            $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->find($id);

            $path = $this->get('kernel')->getProjectDir() . "/web/" . $file->getPath() . "/" . $file->getName();
            $content = file_get_contents($path);

            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$file->getOriginalName());

            $response->setContent($content);

            return $response;
        }
    }

    public function viewFileAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Partition inexistante.');
            }

            $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->find($id);

            $path = $this->get('kernel')->getProjectDir() . "/web/" . $file->getPath() . "/" . $file->getName();

            $response = new BinaryFileResponse($path);

            if(preg_match('#pdf|mp3|wav|aif|txt|jpg|jpeg|png|mov|avi|mp4#i', $file->getType()))
            {
                return $response;
            }

            $content = file_get_contents($path);

            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$file->getOriginalName());

            $response->setContent($content);

            return $response;



        }
    }

    public function deleteFileAction($id, Request $request)
    {
        $file = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->find($id);

        if(!$this->get('security.authorization_checker')->isGranted($file->getRole()->getRole())){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas les autorisations nécessaire pour supprimer ce fichier');
            return $this->redirectToRoute('osel_documents_index', array('idDir' => $file->getDirectory()->getId()));
        }

        $em = $this->getDoctrine()->getManager();
        $path = $this->container->getParameter('kernel.project_dir') . "/web/" . $file->getPath() . "/" . $file->getName();

        rename( $path, $this->container->getParameter('kernel.project_dir') . "/web/" ."files/trash/" . $file->getName() . $file->getOriginalName());
        $em->remove($file);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Le fichier suivant a été supprimé' . $file->getOriginalName());

        if($request->isXmlHttpRequest()) {
            $json = json_encode(array(
                'id'    => $file->getId(),
            ));

            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        $referer = $request->headers->get('referer');;

        return new RedirectResponse($referer);

    }

    public function addDirectoryAction($idDir, $id, Request $request)
    {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CA')){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas acces à cette partie');
            return $this->redirectToRoute('osel_core_home');
        }

        $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($idDir);

        if($dir != null && !$this->get('security.authorization_checker')->isGranted($dir->getRole()->getRole())){
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous ne pouvez pas ajouter un fichier dans ce dossier');
            return $this->redirectToRoute('osel_documents_index');
        }

        if($id <= 0){
            $newDir = new Directory();
        }
        else{
            $newDir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($id);

            if($newDir != null && !$newDir->getEnabled()){
                $request->getSession()->getFlashBag()->add('ERROR', 'Ce dossier est verrouillé');
                return $this->redirectToRoute('osel_documents_index', array(
                    'idDir' => $idDir));
            }
        }

        $fileForm = $this->createForm(DirectoryType::class, $newDir);

        if($request->isMethod('POST') && $fileForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($idDir > 0)
            {
                $newDir->setIdDir($idDir);
            }
            if($dir != null)
            {
                $newDir->setPath($dir->getPath() . "/" . $dir->getName());
                $newDir->setRank($dir->getRank() + 1);
            }
            else{
                $newDir->setPath("files");
                $newDir->setRank(1);
            }

            $root = $newDir->getPath() . "/" . $newDir->getName() ;

            if(!is_dir($root) && $newDir->getId() === null)
            {
                mkdir($root);
            }

            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if($newDir->getId() === null)
                {
                    $newDir->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
                else{
                    $newDir->setLastUser($this->container->get('security.token_storage')->getToken()->getUser());
                }
            }

            if($newDir->getId() !== null)
            {
                $request->getSession()->getFlashBag()->add('success', 'Le dossier a bien été modifié');
            }
            else{
                $request->getSession()->getFlashBag()->add('success', 'Le dossier a bien été ajouté');
            }
            $em->persist($newDir);
            $em->flush();


            return $this->redirectToRoute('osel_documents_index', array(
                'idDir' => $idDir
            ));

        }


        return $this->get('templating')->renderResponse('DocumentBundle:directories:form.html.twig', array(
            'form'      => $fileForm->createView(),
            'idDir'     => $idDir
        ));

    }

    public function activateDirAction($id, Request $request)
    {
        $redirect = new RedirectResponse($request->headers->get('referer'));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')) {
            return $redirect;
        }

        $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($id);

        $form = $this->get('form.factory')->create(ActivateDirType::class, $dir);

        if ($form->handleRequest($request)->isValid()) {
            $subDirs = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findByPath($dir->getPath() . "/" . $dir->getName());
            $files = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findByPath($dir->getPath() . "/" . $dir->getName());
            $em = $this->getDoctrine()->getManager();

            if($dir->getEnabled())
            {
                foreach ($files as $file){
                    $file->setEnabled(true);
                    $em->persist($file);
                }
                foreach ($subDirs as $subDir){
                    $subDir->setEnabled(true);
                    $em->persist($subDir);
                }
            }
            else
            {
                foreach ($files as $file){
                    $file->setEnabled(false);
                    $em->persist($file);
                }
                foreach ($subDirs as $subDir){
                    $subDir->setEnabled(false);
                    $em->persist($subDir);
                }
            }

            $em->persist($dir);
            $em->flush();

            if($request->isXmlHttpRequest())
            {
                $json = json_encode(array(
                    'id'        => $dir->getId(),
                    'enabled'   => $dir->getEnabled(),
                    'data'  => 'dir'
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            $request->getSession()->getFlashBag()->add('success', 'Le Dossier et toutes ces fichiers et sous-dossiers ont bien été (dé)verouillés');

            return $redirect;
        }

        return $this->render('ScoreBundle:score:activate.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function downloadDirAction($id, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if($id < 1)
            {
                throw new NotFoundHttpException('Fichier inexistant.');
            }

            $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($id);

            $path = $this->get('kernel')->getProjectDir() . "/web/" . $dir->getPath() . "/" . $dir->getName();


            $filePath = $this->get('kernel')->getProjectDir() . "/web/files/temp/" . md5(uniqid(null, true)) . ".zip";

            $handle = fopen($filePath, "w");
            fclose($handle);


            $za = new \ZipArchive();
            $za->open($filePath ,\ZipArchive::CREATE|\ZipArchive::OVERWRITE);
            $za->addEmptyDir($path);
            self::folderToZip($path, $za, strlen($this->get('kernel')->getProjectDir() . "/web/" . $dir->getPath()));

            $za->close();
            $content = file_get_contents($filePath);
            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$dir->getOriginalName() . ".zip");

            $response->setContent($content);

            return $response;
        }
    }

    public function deleteDirAction($id, Request $request)
    {
        $dir = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->find($id);
        $em = $this->getDoctrine()->getManager();
        $path = $this->container->getParameter('kernel.project_dir') . "/web/" . $dir->getPath() . "/" .$dir->getName();

        rename( $path, $this->container->getParameter('kernel.project_dir') . "/web/" ."files/trash/" . $dir->getName() . $dir->getOriginalName());

        $subDirs = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:Directory')->findByPath($dir->getPath() . "/" . $dir->getName());
        $files = $this->getDoctrine()->getManager()->getRepository('DocumentBundle:File')->findByPath($dir->getPath() . "/" . $dir->getName());


        foreach ($subDirs as $subDir)
        {
            //unlink($path . "/" . $file->getName());
            $em->remove($subDir);
        }

        foreach ($files as $file)
        {
            //unlink($path . "/" . $file->getName());
            $em->remove($file);
        }

        //rmdir($path);
        $em->remove($dir);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Le dossier suivant a été supprimé: ' . $dir->getOriginalName());


        $referer = $request->headers->get('referer');;

        return new RedirectResponse($referer);

    }

    public function errorLockedAction($idDir, Request $request)
    {
            $request->getSession()->getFlashBag()->add('ERROR', 'Ce Fichier est Verouillé');

            return $this->redirectToRoute('osel_documents_index', array(
                'idDir' => $idDir
            ));
    }

}