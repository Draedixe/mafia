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

    private static $phaseJeu = array('JOUR' => 0, 'PROCES' => 1, 'NUIT' => 2, 'EXECUTION' => 3, 'AUBE' => 4, 'DISCUSSION' => 5, 'SELECTION_DU_NOM' => 6);

    public static function getPhaseJeu()
    {
        return self::$phaseJeu;
    }
}