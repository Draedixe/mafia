<?php

namespace Mafia\PartieBundle\Entity;


abstract class TypeNuitEnum {

    const SEQUENCE = 0;
    const DESCRIPTION_MORTS = 1;
    const NUIT_CLASSIQUE = 2;

    private static $typeNuit = array('SEQUENCE' => 0, 'DESCRIPTION_MORTS' => 1, 'NUIT_CLASSIQUE' => 2);

    public static function getTypeNuit()
    {
        return self::$typeNuit;
    }
}