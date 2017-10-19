<?php
/**
 * Created by PhpStorm.
 * User: davidgoubau
 * Date: 11/06/2017
 * Time: 16:29
 */

namespace OSEL\DocBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class GestionDocEvent extends Event
{
    protected $year;
    protected $date;

    public function __construct($year)
    {
        $this->year     = $year;
        $this->date     = new \DateTime('now');
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        return $this->year = $year;
    }

    public function getDate()
    {
        return $this->date;
    }
}