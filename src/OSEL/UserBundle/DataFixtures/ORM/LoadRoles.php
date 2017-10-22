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
      array('ROLE_USER', 'Musicien'),
      array('ROLE_PRESIDENT', 'Président'),
      array('ROLE_SECRETAIRE', 'Secrétaire'),
      array('ROLE_TRESORIER', 'Trésorier'),
      array('ROLE_CA', 'Membre CA'),
      array('ROLE_PARTITION', 'Archivist'),
      array('ROLE_WEEKEND', 'Responsable Weekend'),
      array('ROLE_ANIMATION', 'Responsable Annimation'),
      array('ROLE_PUB', 'Responsable Pub'),
      array('ROLE_SPONSOR', 'Responsable Sponsor'),
      array('ROLE_WEBMASTER', 'Webmaster'));

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