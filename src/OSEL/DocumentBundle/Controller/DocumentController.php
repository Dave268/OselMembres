<?php
/**
 * Created by PhpStorm.
 * User: davidgoubau
 * Date: 05/06/2017
 * Time: 14:56
 */

namespace OSEL\DocBundle\Controller;

use OSEL\DocBundle\Entity\Document;
use OSEL\DocBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OSEL\DocBundle\Event\DocumentEvents;
use OSEL\DocBundle\Event\GestionDocEvent;
use Symfony\Component\Validator\Constraints\DateTime;


class DocumentController extends Controller
{
    /**
     * @param $idComposer
     * @param $idMusicsheet
     * @param $idPart
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function gestionAction($idYear, $idCategory, $idDocument, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $now = new \DateTime('now');
            if(intval($now->format('m')) < 7)
            {
                $year = intval($now->format('Y')) - 1 .'-' . intval($now->format('Y'));
            }
            else
            {
                $year =  $now->format('Y') . '-' . intval($now->format('Y')) + 1;
            }
            $event = new GestionDocEvent($year);
            $this->get('event_dispatcher')->dispatch(DocumentEvents::GESTION_DOC, $event);

            $years = $this->getDoctrine()->getManager()->getRepository('OSELDocBundle:YearDocument')->findAll();
            $document = new Document();

            //création du formulaire pour ajouter un nouveau document
            $documentForm = $this->get('form.factory')->create(DocumentType::class, $document);

            if ($request->isMethod('POST') && $documentForm->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $document->upload();
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    $document->setUser($user);
                }

                $em->persist($document);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'La document a bien été ajouté');

                return $this->redirectToRoute('osel_documents_gestion');
            }

            return $this->get('templating')->renderResponse('OSELDocBundle:Document:gestion.html.twig', array(
                'years'         => $years,
                'documentForm'  => $documentForm->createView(),
                'selectedPage'  => 'document'
            ));
        }
    }
}