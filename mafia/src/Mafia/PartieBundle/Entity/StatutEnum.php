<?php

namespace Mafia\PartieBundle\Entity;


abstract class StatusEnum {

    /* Garde du corps */
    const PROTEGE = 0;
    /* Marionettiste */
    const CONTROLE = 1;
    /* Pyromane */
    const ESSENCE = 2;
    /*  */
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
    /* Survivant */
    const GILET = 16;
    /* Détective */
    const DETECTE = 17;
    /* */
    const TUE_ANTI_INVUL = 18;
    /* Inspecteur */
    const INSPECTE = 19;
    /* Médecin Légiste */
    const INSPECTE_MORT = 20;
    /* Sheriff */
    const VERIFIE = 21;
    /* Veilleur */
    const OBSERVE = 22;
    /* Vétéran */
    const ALERTE = 23;


    private static $statutPartie = array('PROTEGE' => 0, 'CONTROLE' => 1, 'ESSENCE' => 2, 'SAUVE' => 3, 'ECHANGE' => 4, 'PIEGE' => 5, 'BLOQUE' => 6, 'EMPRISONNE' => 7, 'TUE' => 8,
        'ROLE_CHANGE' => 9,'CIBLE_CONTROLE' => 10, 'CIBLE_MASSE' => 11, 'VOL_ROLE' => 12, 'FAIRE_CHIER' => 13, 'CONVERTIR' => 14, 'SAUVE_CONVERTIR' => 15, 'GILET' => 16, 'DETECTE' => 17,
        'TUE_ANTI_INVUL' => 18, 'INSPECTE' => 19, 'INSPECTE_MORT' => 20, 'VERIFIE' => 21, 'OBSERVE' => 22, 'ALERTE' => 23
    );

    public static function getStatusPartie()
    {
        return self::$statutPartie;
    }
}