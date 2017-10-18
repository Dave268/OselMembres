<?php

namespace OSEL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function indexAction($page, $criteria, $desc, $active, $nbPerPage)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
        {
            $listUsers = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->getUsers($page, $nbPerPage, $active, $criteria, $desc);

        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            $listUsers = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->getUsers($page, $nbPerPage, true, $criteria, $desc);
        }
        else
        {

        }

        $nbPages = ceil(count($listUsers) / $nbPerPage);
        if ($page > $nbPages)
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

    	
        return $this->get('templating')->renderResponse('OSELUserBundle:User:index.html.twig', array(
        	'listUsers' 	=> $listUsers,
            'page'          => $page,
            'criteria'      => $criteria,
            'desc'          => $desc,
            'active'        => $active,
            'nbPages'       => $nbPages,
			'selectedPage'	=> 'membres'));
    }

    public function viewAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') && $this->container->get('security.token_storage')->getToken()->getUser()->getId() == $id) {
            if ($id < 1) {
                throw new NotFoundHttpException('Page inexistante.');
            }
            $user = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:User')->findOneBy(array('id' => $id));

            return $this->get('templating')->renderResponse('OSELUserBundle:User:view.html.twig', array(
                'user' => $user,
                'selectedPage' => 'membres'));
        }
    }
}