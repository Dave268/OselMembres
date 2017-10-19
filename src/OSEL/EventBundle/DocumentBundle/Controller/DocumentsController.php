<?php
/**
 * Created by PhpStorm.
 * User: davidgoubau
 * Date: 05/06/2017
 * Time: 14:56
 */

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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTITION')) {
            $composers = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->findAll();
            $musicsheet = new Musicsheet();
            $composer = new Composer();
            $type = new SheetType();
            $instrument = new Instrument();

            //Gestion du formulaire pour ajouter une partition
            $musicsheetForm = $this->get('form.factory')->create(MusicsheetType::class, $musicsheet);

            if ($request->isMethod('POST') && $musicsheetForm->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $musicsheet->upload();
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    $musicsheet->setUser($this->container->get('security.token_storage')->getToken()->getUser());
                }

                $em->persist($musicsheet);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'La partition a bien été rajoutée');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }


            //Gestion du formulaire pour ajouter un Compositeur
            $composerForm = $this->get('form.factory')->create(ComposerType::class, $composer);

            if ($request->isMethod('POST') && $composerForm->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($composer->setComposer($composer->getLastName() . ' ' . $composer->getName())) {
                    $directory = __DIR__ . '/bundles/musicsheets/index/' . $composer->getComposer();
                    if (!is_dir($directory)) {
                        if (mkdir($directory)) {
                            $request->getSession()->getFlashBag()->add('notice', 'Le dossier a bien été crée.');

                            if (chmod($directory, 0777)) {
                                $request->getSession()->getFlashBag()->add('notice', 'Les droits du dossier ont bien été modifié.');
                            }
                        }
                    } else {
                        $request->getSession()->getFlashBag()->add('ERROR', 'Le dossier existait déjà.');
                    }

                    $em->persist($composer);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', 'Le compositeur a bien été bien ajouté.');

                    return $this->redirectToRoute('osel_musicsheet_gestion');
                } else {
                    $request->getSession()->getFlashBag()->add('notice', 'Le compositeur existe déjà ou une autre erreur est survenue');

                    return $this->redirectToRoute('osel_musicsheet_gestion');
                }

            }


            //Gestion du formulaire pour ajouter un type de partition
            $typeForm = $this->get('form.factory')->create(SheetTypeType::class, $type);

            if ($request->isMethod('POST') && $typeForm->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Type bien ajouté.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }

            //Gestion du formulaire pour ajouter un instrument
            $instrumentForm = $this->get('form.factory')->create(InstrumentType::class, $instrument);

            if ($request->isMethod('POST') && $instrumentForm->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($instrument);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'L\'instrument a bien été ajouté.');

                return $this->redirectToRoute('osel_musicsheet_gestion');
            }


            return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Musicsheet:gestion.html.twig', array(
                'composers' => $composers,
                'musicsheetForm' => $musicsheetForm->createView(),
                'composerForm' => $composerForm->createView(),
                'typeForm' => $typeForm->createView(),
                'instrumentForm' => $instrumentForm->createView(),
                'selectedPage' => 'partition'
            ));
        }
    }
}