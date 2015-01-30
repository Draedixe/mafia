<?php

namespace Mafia\PartieBundle\Entity;


abstract class PhaseJeuEnum {

    const JOUR = 0;
    const TRIBUNAL = 1;
    const NUIT = 2;
    const EXECUTION = 3;
    const AUBE = 4;
    const DISCUSSION = 5;
    const SELECTION_DU_NOM = 6;

    private static $phaseJeu = array('JOUR' => 0, 'PROCES' => 1, 'NUIT' => 2, 'EXECUTION' => 3, 'AUBE' => 4, 'DISCUSSION' => 5, 'SELECTION_DU_NOM' => 6);

    public static function getPhaseJeu()
    {
        return self::$phaseJeu;
    }
}