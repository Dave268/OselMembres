<?php
// src/Osel/UserBundle/DataFixtures/ORM/LoadRoles.php

namespace OSEL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Osel\UserBundle\Entity\Roles;

class LoadRoles implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listRoles = array(
      array('USER_ROLE', 'Musicien'),
      array('USER_PRESIDENT', 'Président'),
      array('USER_SECRETAIRE', 'Secrétaire'),
      array('USER_TRESORIER', 'Trésorier'),
      array('USER_CA', 'Membre CA'),
      array('USER_PARTITION', 'Archivist'),
      array('USER_WEEKEND', 'Responsable Weekend'),
      array('USER_ANIMATION', 'Responsable Annimation'),
      array('USER_PUB', 'Responsable Pub'),
      array('USER_SPONSOR', 'Responsable Sponsor'),
      array('USER_WEBMASTER', 'Webmaster'));

    foreach ($listRoles as $role) {
      // On crée l'utilisateur
      $roles = new Roles;
      $roles->setRole($role[0]);
      $manager->persist($roles);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}