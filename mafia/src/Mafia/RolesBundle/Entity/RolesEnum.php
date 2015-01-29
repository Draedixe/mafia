<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 14/01/2015
 * Time: 23:29
 */

namespace Mafia\RolesBundle\Entity;


/**
 * <p>Enumerateurs des rôles</br>
 * Permet l'identification du role, afin d'utiliser les bons pouvoirs<p>
 */
abstract class RolesEnum{

    // VILLE \\
    const SHERIFF = 1;
    const INSPECTEUR = 2;
    const DOCTEUR = 3;
    const GARDE_DU_CORPS = 4;
    const CITOYEN = 5;
    const CONDUCTEUR_DE_BUS = 6;
    const MEDECIN_LEGISTE = 7;
    const DETECTIVE = 8;
    const MARSHALL = 9;
    const MACON = 10;
    const MACON_CHEF = 11;
    const GARDIEN_DE_PRISON = 12;
    const VEILLEUR = 13;
    const CRIEUR = 14;
    const ESCORT = 15;
    const ESPION = 16;
    const JUSTICIER = 17;
    const MAIRE = 18;
    const VETERAN = 19;

    // MAFIA \\
    const AGENT = 20;
    const PILLEUR = 21;
    const MAITRE_CHANTEUR = 22;
    const CONSEILLER = 23;
    const PROSTITUEE = 24;
    const MAITRE_DU_DEGUISEMENT = 25;
    const CONTREFACTEUR = 26;
    const PARRAIN = 27;
    const CONCIERGE = 28;
    const KIDNAPPER = 29;
    const MAFIOSO = 30;

    // NEUTRE \\
    const AMNESIQUE = 31;
    const PYROMANE = 32;
    const AUDITEUR = 33;
    const CULTISTE = 34;
    const BOURREAU = 35;
    const BOUFFON = 36;
    const JUGE = 37;
    const TUEUR_DE_MASSE = 38;
    const TUEUR_EN_SERIE = 39;
    const SURVIVANT = 40;
    const MARIONETTISTE = 41;
    const SORCIER = 42;

    // TRIADE \\
    const ADMINISTRATEUR = 43;
    const IMPOSTEUR = 44;
    const DRAGON = 45;
    const TUEUR_A_GAGE = 46;
    const FAUSSAIRE = 47;
    const MAITRE_ENCENS = 48;
    const INFORMATEUR = 49;
    const INTERROGATEUR = 50;
    const LIAISON = 51;
    const MAITRE_SILENCE = 52;
    const AVANT_GARDE = 53;

    private static $nomsPouvoirs = array('SHERIFF' => 1, 'INSPECTEUR' => 2, 'DOCTEUR' => 3, 'GARDE_DU_CORPS' => 4,'CITOYEN' => 5, 'CONDUCTEUR_DE_BUS' => 6, 'MEDECIN_LEGISTE' => 7, 'DETECTIVE' => 8, 'MARSHALL' => 9,
                                    'MACON' => 10, 'MACON_CHEF' => 11, 'GARDIEN_DE_PRISON' => 12, 'VEILLEUR' => 13, 'CRIEUR' => 14, 'ESCORT' => 15, 'ESPION' => 16, 'JUSTICIER' => 17, 'MAIRE' => 18, 'VETERAN' => 19,
                                    'AGENT' => 20, 'PILLEUR' => 21, 'MAITRE_CHANTEUR' => 22, 'CONSEILLER' => 23, 'PROSTITUERR' => 24, 'MAITRE_DU_DEGUISEMENT' => 25, 'CONTREFACTEUR' => 26, 'PARRAIN' => 27, 'CONCIERGE' => 28,
                                    'KIDNAPPER' => 29, 'MAFIOSO' => 30, 'AMNESIQUE' => 31, 'PYROMANE' => 32, 'AUDITEUR' => 33, 'CULTISTE' => 34, 'BOURREAU' => 35, 'BOUFFON' => 36, 'JUGE' => 37,
                                    'TUEUR_DE_MASSE' => 38, 'TUEUR_EN_SERIE' => 39, 'SURVIVANT' => 40,  'MARIONETTISTE' => 41, 'SORCIER' => 42, 'ADMINISTRATEUR' => 43, 'IMPOSTEUR' => 44,
                                    'DRAGON' => 45, 'TUEUR_A_GAGE' => 46, 'FAUSSAIRE' => 47, 'MAITRE_ENCENS' => 48, 'INFORMATEUR' => 49, 'INTERROGATEUR' => 50,
                                    'LIAISON' => 51, 'MAITRE_SILENCE' => 52, 'AVANT_GARDE' => 53);

    public static function getNomsPouvoirs()
    {
        return self::$nomsPouvoirs;
    }
}