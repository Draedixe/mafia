<?php

namespace Mafia\PartieBundle\Entity;


abstract class DebutPartieEnum {

    const NUIT = 0;
    const JOUR = 1;
    const JOUR_SANS_LYNCHAGE = 2;

    private static $debutPartie = array('NUIT' => 0, 'JOUR' => 1, 'JOUR_SANS_LYNCHAGE' => 2);

    public static function getDebutPartie()
    {
        return self::$debutPartie;
    }
}