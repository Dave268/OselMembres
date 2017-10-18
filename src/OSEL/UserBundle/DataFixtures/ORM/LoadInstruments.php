<?php
// src/OC/UserBundle/DataFixtures/ORM/LoadInstruments.php

namespace OSEL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Osel\UserBundle\Entity\Instruments;

class LoadInstruments implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listInstruments = array('Violon', 'Alto', 'Violoncelle', 'Contrebasse', 'Flute', 'Hautbois', 'Cor Anglé', 'Clarinette', 'Clarinette Basse', 'Basson', 'Cor', 'Trompette', 'Trombone', 'Percussions', 'Harpe', 'Piano');

    foreach ($listInstruments as $name) {
      // On crée l'utilisateur
      $instrument = new Instruments;
      $instrument->setInstrument($name);
      $manager->persist($instrument);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}