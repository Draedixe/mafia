<?php

namespace Mafia\PartieBundle\Entity;


abstract class StatusEnum {

    const PROTEGE = 0;
    const CONTROLE = 1;
    const ESSENCE = 2;
    const SAUVE = 3;
    const ECHANGE = 4;
    const PIEGE = 5;
    const BLOQUE = 6;
    const EMPRISONNE = 7;
    const TUE = 8;

    private static $statutPartie = array('PROTEGE' => 0, 'CONTROLE' => 1, 'ESSENCE' => 2, 'SAUVE' => 3, 'ECHANGE' => 4, 'PIEGE' => 5, 'BLOQUE' => 6, 'EMPRISONNE' => 7, 'TUE' => 8);

    public static function getStatusPartie()
    {
        return self::$statutPartie;
    }
}