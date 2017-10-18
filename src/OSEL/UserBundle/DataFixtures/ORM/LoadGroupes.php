<?php
// src/Osel/UserBundle/DataFixtures/ORM/LoadGroupes.php

namespace OSEL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Osel\UserBundle\Entity\Groupes;

class LoadGroupes implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listGroupes = array('Violons 1', 'Violons 2', 'Altos', 'Violoncelles', 'Contrebasses', 'Flutes', 'Hautbois', 'Clarinettes', 'Bassons', 'Cors', 'Trompettes', 'Trombones', 'Percussions', 'Renforts', 'Cordes', 'Harmonie', 'CA', 'Cuivres', 'Bois');

    foreach ($listGroupes as $name) {
      // On crée l'utilisateur
      $groupe = new Groupes;
      $groupe->setGroupe($name);
      $manager->persist($groupe);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}