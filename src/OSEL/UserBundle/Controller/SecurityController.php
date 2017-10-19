<?php
// src/OSEL/UserBundle/Controller/SecurityController.php;

namespace OSEL\UserBundle\Controller;

use OSEL\UserBundle\Entity\User;
use OSEL\UserBundle\Entity\Temp;
use OSEL\UserBundle\Entity\Roles;
use OSEL\UserBundle\Form\UserType;
use OSEL\UserBundle\Form\UserCompleteType;
use OSEL\UserBundle\Form\ShortUserType;
use OSEL\UserBundle\Form\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;





class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          return $this->redirectToRoute('osel_core_home');
        }
        
        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('OSELUserBundle:Security:login.html.twig', array(
          'last_username' => $authenticationUtils->getLastUsername(),
          'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function registerAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }

        $user = new User();

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $userName = $this->container->get('security.token_storage')->getToken()->getUser()->getName();
        }
        else{
            $userName = "Administrateur de site de l'Osel";
        }

            $form = $this->get('form.factory')->create(UserType::class, $user);

            if ($form->handleRequest($request)->isValid()) 
            {
                if($user->getRoles() == null)
                {
                    $role = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Roles')->findOneBy(array('role' => 'USER_ROLE'));
                    $user->addUserRole($role);
                    $role->setUser($user);
                }

                $pwd= $this->get('security.encoder_factory')->getEncoder($user)->encodePassword(md5(uniqid(null, true)), $user->getSalt());
				
                $user->defineRest($pwd);
                if($user->getActif())
                {
                    $temp = new Temp();
                    $temp->setRole("setpwd");
                    $temp->setValidity("172800");
                    $temp->setActive(true);
                    $user->addTemp($temp);
                    $temp->setUser($user);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mail = $this->container->get('OSEL_User.registerMail');

                if($user->getActif())
                {
                    $mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), $userName, $user->getEmail(), $user->getUsername());
                }

                $request->getSession()->getFlashBag()->add('success', 'Un nouveau membre à bien été inscrit');

                return $this->redirect($this->generateUrl('register'));
            }


			return $this->render('OSELUserBundle:User:add.html.twig', array(
				'form' => $form->createView(),
				'selectedPage'	=> 'membres'
				));  
    }

    public function modifyAction($id, Request $request)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE') || $id != $this->get('security.token_storage')->getToken()->getUser()->getId())
        {
            $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');
            return $this->redirect($this->generateUrl('osel_core_home'));
        }

            $form = $this->get('form.factory')->create(UserType::class, $user);
			
			if ($form->handleRequest($request)->isValid()) {
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($user);
			    $em->flush();

			    $request->getSession()->getFlashBag()->add('success', 'Les modifications ont bien été enregistré');

				if($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
				{
					return $this->redirect($this->generateUrl('osel_user_index'));
				}
				else
				{
					return $this->redirect($this->generateUrl('osel_user_view', array('id' => $id)));
				}
			}

        return $this->render('OSELUserBundle:User:add.html.twig', array(
          'form' => $form->createView(),
		  'selectedPage'	=> 'membres'
        ));
    }

    public function modifyCompleteAction($id, Request $request)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id));

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            $form = $this->get('form.factory')->create(UserCompleteType::class, $user);

            if ($form->handleRequest($request)->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                if($user->getActif())
                {
                    $temp = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Temp')->findOneBy(array('user' => $user, 'role' => 'setpwd'));

                    if($temp == null)
                    {
                        $temp = new Temp();
                    }
                    else
                    {
                        $temp->setDateAdd(new \DateTime('now'));
                        $temp->setSha(md5(uniqid(null, true)));
                    }

                    $temp->setActive(true);
                    $user->addTemp($temp);
                    $temp->setUser($user);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $mail = $this->container->get('OSEL_User.registerMail');
                    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        $userName = $this->container->get('security.token_storage')->getToken()->getUser()->getName();
                    }
                    else{
                        $userName = "Administrateur de site de l'Osel";
                    }

                    $mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), $userName, $user->getEmail(), $user->getUsername());
                        $request->getSession()->getFlashBag()->add('notice', 'Un nouveau mail d\'inscription a été envoyé');
                    
                }

                if($request->isXmlHttpRequest())
                {
                    $json = json_encode(array(
                        'id'    => $user->getId(),
                        'actif' => $user->getActif()
                    ));

                    $response = new Response($json);
                    $response->headers->set('Content-Type', 'application/json');

                    return $response;
                }

				if($user->getActif())
				{
					$request->getSession()->getFlashBag()->add('success', 'Le membre a bien été activé');
				}
				else
				{
					$request->getSession()->getFlashBag()->add('success', 'Le membre a bien été désactivé');
				}

                return $this->redirect($this->generateUrl('osel_user_index'));
            }
        }

        return $this->render('OSELUserBundle:User:modifyComplete.html.twig', array(
            'form' => $form->createView(),
            'selectedPage'	=> 'membres'
        ));
    }
	
	public function deleteAction($id, Request $request)
    {
		if(!$user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id))) {
            $request->getSession()->getFlashBag()->add('ERROR', 'La requête pour sélectionner un membre n\'a pas fonctionné');
        }
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE')) {
                $request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas le droit de supprimer un membre');
                return $this->redirect($this->generateUrl('osel_user_index'));
            }


                $em = $this->getDoctrine()->getManager();
                foreach ($user->getTemps() as $temp)
                {
                    $em->remove($temp);
                }
				foreach ($user->getLogins() as $login)
                {
					$em->remove($login);
                }
                foreach ($user->getSubscribeEvents() as $event)
                {
                    $em->remove($event);
                }
                $em->remove($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Le membre a été supprimé');

                return $this->redirect($this->generateUrl('osel_user_index'));
	}

	public function setPassAction($set, $id, $sha, Request $request)
    {
        if ($id < 1) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas.");
        }

        $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id));

        //on check si c'est un set ou un reset
        if($set)
        {
            $action = "Nouveau Mot de Passe";
            $temp = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Temp')->findOneBy(array('sha' => $sha, 'role' => 'setpwd'));

        }
        else
        {
            $action = "Reset du Mot de Passe";
            $temp = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Temp')->findOneBy(array('sha' => $sha, 'role' => 'resetpwd'));
        }

        if($temp == null OR $user == null)
        {
            throw $this->createNotFoundException("Cet URL n'est pas valide");
        }
        else
        {
            if ($temp->getUser()->getId() == $user->getId()) {
                if ($temp->getActive()) {
                    $current = new \DateTime('now');
                    $tempValid = $temp->getDateAdd()->getTimestamp() + $temp->getValidity();

                    if ($current->getTimestamp() < $tempValid) {
                        $formData = array();
                        $form = $this->get('form.factory')->createBuilder(FormType::class, $formData)
                            ->add('pwd1', PasswordType::class)
                            ->add('pwd2', PasswordType::class)
                            ->add('Envoyer', SubmitType::class, array(
                                "disabled" => "true"
                            ))
                            ->getForm();

                        if ($request->isMethod('POST')) {
                            $form->handleRequest($request);
                            $formData = $form->getData();

                            if ($formData['pwd1'] == $formData['pwd2']) {
                                $pwd = $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($formData['pwd1'], $user->getSalt());

                                $user->setPassword($pwd);
                                $temp->setActive(false);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($user);
                                $em->flush();

                                $request->getSession()->getFlashBag()->add('notice', 'Votre nouveau mot de passe à bien été validé');

                                return $this->redirect($this->generateUrl('osel_core_home'));
                            } else {
                                $request->getSession()->getFlashBag()->add('ERROR', 'Les deux mots de passe ne sont pas identique');
                            }

                        }
                    } else {
                        $temp->setActive(false);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($temp);
                        $em->flush();
                        throw $this->createNotFoundException("Ce lien n'est plus valide");
                    }
                } else {
                    throw $this->createNotFoundException("Ce lien n'est plus valide");
                }

            } else {
                throw $this->createNotFoundException("l'url ne correspond pas à un reset de mot de passe");
            }
        }

        return $this->render('OSELUserBundle:Security:pwdReset.html.twig', array(
            'title' => $action,
            'form'	=> $form->createView()
        ));
    }

    public function sendResetMailAction(Request $request)
    {

        $formData = array();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $formData)
            ->add('mail',           EmailType::class)
            ->add('Envoyer',        SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $formData = $form->getData();

            $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('email' => $formData['mail']));

			if ($user == null){
				$request->getSession()->getFlashBag()->add('ERROR', 'Aucun membre n\'est inscrit avec cette adresse e-mail');
				return $this->redirect($this->generateUrl('send_reset_mail'));
			   }

			if (!$user->getActif()) {
				return $this->redirect($this->generateUrl('send_reset_mail'));
			}

				$temp = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Temp')->findOneBy(array('user' => $user, 'role' => 'resetpwd'));

				if($temp == null)
				{
					$temp = new Temp();
				}
				else
				{
					$temp->setDateAdd(new \DateTime('now'));
					$temp->setSha(md5(uniqid(null, true)));
				}

				$temp->setRole("resetpwd");
				$temp->setValidity("14400");
				$temp->setActive(true);
				$user->addTemp($temp);
				$temp->setUser($user);

				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$mail = $this->container->get('OSEL_User.resetMail');

				$mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), 'Administrateur du site de l\'Osel', $user->getEmail());
					$request->getSession()->getFlashBag()->add('notice', 'Un mail vous a été envoyé avec un lien pour reseter votre mot de passe');
				

				return $this->redirect($this->generateUrl('osel_core_home'));
			
			}


        return $this->render('OSELUserBundle:Security:sendResetMail.html.twig', array(
            'form'	=> $form->createView()
        ));
    }

    public function sendInscriptionMailAction($id, Request $request)
    {

            $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id));

		    if ($user == null){
                $request->getSession()->getFlashBag()->add('ERROR', 'ce membre n\'existe pas');
                return $this->redirect($this->generateUrl('osel_user_index'));
            }

                $temp = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Temp')->findOneBy(array('user' => $user, 'role' => 'setpwd'));

                if($temp == null)
                {
                    $temp = new Temp();
                }
                else
                {
                    $temp->setDateAdd(new \DateTime('now'));
                    $temp->setSha(md5(uniqid(null, true)));
                }

                $temp->setActive(true);
                $user->addTemp($temp);
                $temp->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mail = $this->container->get('OSEL_User.registerMail');
                if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                {
                    $userName = $this->container->get('security.token_storage')->getToken()->getUser()->getName();
                }
                else{
                    $userName = "Administrateur de site de l'Osel";
                }

                $mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), $userName, $user->getEmail(), $user->getUsername());
                    $request->getSession()->getFlashBag()->add('notice', 'Un nouveau mail d\'inscription a été envoyé');

                return $this->redirect($this->generateUrl('osel_user_index'));
    }
}