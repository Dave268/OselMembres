<?php
/**
 * Created by PhpStorm.
 * User: choco268
 * Date: 06.11.17
 * Time: 16:49
 */

namespace OSEL\ScoreBundle\Controller;


use OSEL\MusicsheetBundle\Entity\Musicsheet;
use OSEL\MusicsheetBundle\Entity\Composer;
use OSEL\MusicsheetBundle\Entity\SheetType;
use OSEL\MusicsheetBundle\Entity\Instrument;
use OSEL\MusicsheetBundle\Form\InstrumentType;
use OSEL\MusicsheetBundle\Form\MusicsheetType;
use OSEL\MusicsheetBundle\Form\ComposerType;
use OSEL\MusicsheetBundle\Form\SheetTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ScoreController extends Controller
{
    public function gestionAction($idComposer, $idScore, $idPart, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTITION'))
        {
            $composers  = $this->getDoctrine()->getManager()->getRepository('OSELMusicsheetBundle:Composer')->myFindAll();
            $musicsheet     = new Musicsheet();
            $composer       = new Composer();

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


            return $this->get('templating')->renderResponse('OSELScoreBundle:Score:gestion.html.twig', array(
                'composers'			=> $composers,
                'musicsheetForm'    => $musicsheetForm->createView(),
                'composerForm'      => $composerForm->createView(),
            ));
        }
    }

    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') || !$this->get('security.authorization_checker')->isGranted('ROLE_USER'))
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
        return $this->get('templating')->renderResponse('OSELMusicsheetBundle:Score:index.html.twig', array(
            'composers'			=> $composers,
            'parts'             => $parts,
            'selectedPage'		=> 'partition'
        ));
    }

}