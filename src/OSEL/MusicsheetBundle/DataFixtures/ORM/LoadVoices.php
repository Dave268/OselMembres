<?php
// src/Osel/UserBundle/DataFixtures/ORM/LoadRoles.php

namespace Osel\MusicsheetBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Osel\MusicsheetBundle\Entity\Voices;

class LoadRoles implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listVoices = array(
      'Violons 1',
      'Violons 2',
      'Altos',
      'Violoncelles',
      'Contrebasses',
      'Flutes',
      'Hautbois',
      'Cor Anglé',
      'Clarinettes',
      'Clarinette Basse',
      'Bassons',
      'Cors',
      'Trompettes',
      'Percussions',
      'Harpe');

    foreach ($listVoices as $voice) {
      // On crée l'utilisateur
      $voices = new Voices;
      $voices->setVoice($voice);
      $manager->persist($voices);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}