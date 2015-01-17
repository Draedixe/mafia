<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/01/2015
 * Time: 00:07
 */

namespace Mafia\RolesBundle\Entity;


abstract class FactionEnum {

    const VILLE = 0;
    const MAFIA = 1;
    const TRIADE = 2;
    const NEUTRE = 3;

    private static $nomsFactions = array('VILLE' => 0, 'MAFIA' => 1, 'TRIADE' => 2, 'NEUTRE' => 3);

    public static function getNomsFactions()
    {
        return self::$nomsFactions;
    }
} 