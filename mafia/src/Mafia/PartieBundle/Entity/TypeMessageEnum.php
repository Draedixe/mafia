<?php

namespace Mafia\PartieBundle\Entity;


abstract class TypeMessageEnum {

    const TOUS = 0;
    const MAFIA = 1;
    const TRIADE = 2;
    const CULTE = 3;
    const NUIT = 4;
    const MACON = 5;

    private static $typeMessage = array('TOUS' => 0, 'MAFIA' => 1, 'TRIADE' => 2, 'CULTE' => 3, 'NUIT' => 4, 'MACON' => 5);

    public static function getTypeMessage()
    {
        return self::$typeMessage;
    }
}