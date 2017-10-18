<?php

namespace OSEL\UserBundle\Listener;


use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use OSEL\UserBundle\Entity\User;
use OSEL\UserBundle\Entity\logins;

class AuthenticationListener
{

    /** @var \Symfony\Component\Security\Core\Security */
    private $security;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor
     *
     * @param Security $security
     * @param Doctrine        $doctrine
     */
    public function __construct(AuthorizationChecker $security, Doctrine $doctrine)
    {
        $this->security = $security;
        $this->em       = $doctrine->getManager();
    }


    /**
     * onAuthenticationFailure
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure( AuthenticationFailureEvent $event )
    {
        // executes on failed login
    }

    /**
     * onAuthenticationSuccess
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess( InteractiveLoginEvent $event )
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {

            if($user instanceof User)
            {
                $login = new logins();


                $login->setName($user->getName() . ' ' . $user->getLastname());
                $user->addLogin($login);
                $this->em->persist($user);
                $this->em->persist($login);
                $this->em->flush();
            }
            else
            {
                $login = new logins();
                $login->setName($user->getUsername());
                $this->em->persist($login);
                $this->em->flush();
            }
        }


        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user has logged in using remember_me cookie
        }
    }
}