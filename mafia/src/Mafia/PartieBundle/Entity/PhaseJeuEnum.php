<?php

namespace Mafia\PartieBundle\Entity;


abstract class PhaseJeuEnum {

    const JOUR = 0;
    const TRIBUNAL_DEFENSE = 1;
    const TRIBUNAL_VOTE = 2;
    const RESULTAT_VOTE = 3;
    const NUIT = 4;
    const EXECUTION = 5;
    const AUBE = 6;
    const DISCUSSION = 7;
    const SELECTION_DU_NOM = 8;

    private static $phaseJeu = array('JOUR' => 0, 'TRIBUNAL_DEFENSE' => 1, 'TRIBUNAL_VOTE' => 2, 'RESULTAT_VOTE' => 3, 'NUIT' => 4, 'EXECUTION' => 5, 'AUBE' => 6, 'DISCUSSION' => 7,'SELECTION_DU_NOM' => 8);

    public static function getPhaseJeu()
    {
        return self::$phaseJeu;
    }
}