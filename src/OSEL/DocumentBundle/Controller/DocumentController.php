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
use OSEL\DocumentBundle\Form\DirectoryType;
use OSEL\DocumentBundle\Form\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    public function indexAction($idDir, $rank, $criteria, $order)
    {
        return $this->render('DocumentBundle:documents:index.html.twig');
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
            if($file->upload($dir->getPath))
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
                'id' => $idDir,
                'rank' => $dir->getRank()
            ));

        }

        return $this->get('templating')->renderResponse('DocumentBundle:files:form.html.twig', array(
            'form'    => $fileForm->createView()
        ));

    }

    public function addDirectoryAction($idDir, Request $request)
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

        $newDir = new Directory();
        $fileForm = $this->createForm(DirectoryType::class, $newDir);

        if($request->isMethod('POST') && $fileForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($idDir > 0)
            {
                $newDir->setIdDir($idDir);
            }
            if($dir != null)
            {
                $newDir->setPath($dir->getPath . "/" . $dir->getName());
                $newDir->setRank($dir->getRank() + 1);
            }
            else{
                $newDir->setPath("files");
                $newDir->setRank(1);
            }

            $root = $newDir->getPath() . "/" . $newDir->getName();

            if(!is_dir($root) && $newDir->getId() === null)
            {
                mkdir($root);
            }
            elseif (!is_dir($root) && $newDir->getId() !== null)
            {}

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
                'id' => $idDir
            ));

        }

        return $this->get('templating')->renderResponse('DocumentBundle:directories:form.html.twig', array(
            'form'    => $fileForm->createView()
        ));

    }
}