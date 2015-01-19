<?php

namespace Mafia\PartieBundle\Entity;


abstract class TypeJugementEnum {

    const MAJORITE = 0;
    const PROCES = 1;
    const SCRUTIN_SECRET = 2;
    const SCRUTIN_SECRET_PROCES = 3;

    private static $typeJugement = array('MAJORITE' => 0, 'PROCES' => 1, 'SCRUTIN_SECRET' => 2, 'SCRUTIN_SECRET_PROCES' => 3);

    public static function getTypeJugement()
    {
        return self::$typeJugement;
    }
}