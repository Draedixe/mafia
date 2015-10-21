<?php

namespace Mafia\PartieBundle\Entity;


abstract class StatusEnum {

    /* Garde du corps */
    const PROTEGE = 0;
    /* Marionettiste */
    const CONTROLE = 1;
    /* Pyromane */
    const ESSENCE = 2;
    /* Survivant */
    const SAUVE = 3;
    /* Conducteur de Bus */
    const ECHANGE = 4;
    /* Contrefacteur */
    const PIEGE = 5;
    /*  */
    const BLOQUE = 6;
    /* Gardien de Prison */
    const EMPRISONNE = 7;
    /* Tueur en Série */
    const TUE = 8;
    /* Auditeur */
    const ROLE_CHANGE = 9;
    /* Marionettiste */
    const CIBLE_CONTROLE = 10;
    /* Tueur de Masse */
    const CIBLE_MASSE = 11;
    /* Amnésique */
    const VOL_ROLE = 12;
    /* Bouffon */
    const FAIRE_CHIER = 13;
    /* Cultiste */
    const CONVERTIR = 14;
    /* Gourou */
    const SAUVE_CONVERTIR = 15;


    private static $statutPartie = array('PROTEGE' => 0, 'CONTROLE' => 1, 'ESSENCE' => 2, 'SAUVE' => 3, 'ECHANGE' => 4, 'PIEGE' => 5, 'BLOQUE' => 6, 'EMPRISONNE' => 7, 'TUE' => 8,
        'ROLE_CHANGE' => 9,'CIBLE_CONTROLE' => 10, 'CIBLE_MASSE' => 11, 'VOL_ROLE' => 12, 'FAIRE_CHIER' => 13, 'CONVERTIR' => 14, 'SAUVE_CONVERTIR' => 15
    );

    public static function getStatusPartie()
    {
        return self::$statutPartie;
    }
}