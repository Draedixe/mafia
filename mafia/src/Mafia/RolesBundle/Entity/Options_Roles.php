<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/01/2015
 * Time: 00:55
 */

namespace Mafia\RolesBundle\Entity;

abstract class Options_Roles {

    private static $options = array(
        Roles_Enum::SHERIFF =>  array(
                    Options_Roles_Enum::SHERIFF_DETECT_MAFIA_TRIADE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_TUEUR_EN_SERIE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_PYROMANE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_CULTE => 1,
                    Options_Roles_Enum::SHERIFF_DETECT_TUEUR_DE_MASSE => 1
        ),
        Roles_Enum::CITOYEN => array(
            Options_Roles_Enum::CITOYEN_GILET_PARE_BALLE => 1,
            Options_Roles_Enum::CITOYEN_VICTOIRE_EGALITE_MAFIA => 1
        ),
        Roles_Enum::DOCTEUR => array(
            Options_Roles_Enum::DOCTEUR_SAIT_CIBLE_ATTAQUEE => 1,
            Options_Roles_Enum::DOCTEUR_EMPECHE_CONVERSION_CULTE => 0,
            Options_Roles_Enum::DOCTEUR_SAIT_CONVERTI_CULTE => 0,
            Options_Roles_Enum::DOCTEUR_DEVIENT_SORCIER_SI_CULTE => 0
        ),
        Roles_Enum::MARSHALL => array(
            Options_Roles_Enum::MARSHALL_NOMBRE_LYNCHAGE => 1,
            Options_Roles_Enum::MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE => 3
        ),
        Roles_Enum::CONDUCTEUR_DE_BUS => array(
            Options_Roles_Enum::CONDUCTEUR_PEUT_SE_CIBLER => 0
        ),
        Roles_Enum::CRIEUR => array(
            Options_Roles_Enum::CRIEUR_EXCLU_ALEATOIRE => 0
        ),
        Roles_Enum::GARDE_DU_CORPS => array(
            Options_Roles_Enum::GARDE_IGNORE_INVULNERABLE => 1,
            Options_Roles_Enum::GARDE_INSOIGNABLE => 1,
            Options_Roles_Enum::GARDE_NON_CONVERSION_CULTE => 0
        ),
        Roles_Enum::INSPECTEUR => array(
            Options_Roles_Enum::INSPECTEUR_DECOUVRE_ROLE_EXACT => 0
        ),
        Roles_Enum::DETECTIVE => array(
            Options_Roles_Enum::DETECTIVE_IGNORE_IMMUNITE_DETECTION => 1
        ),
        Roles_Enum::ESCORT => array(
            Options_Roles_Enum::ESCORT_INBLOCABLE => 0,
            Options_Roles_Enum::ESCORT_DETECTE_INSENSIBLE_BLOCAGE => 0
        ),
        Roles_Enum::ESPION => array(
            Options_Roles_Enum::ESPION_EXCLU_ALEATOIRE => 0,
            Options_Roles_Enum::ESPION_VOIT_CIBLE_MAFIA_TRIADE => 0,
            Options_Roles_Enum::ESPION_VOIT_MEURTRE_MAFIA_TRIADE => 1
        ),
        Roles_Enum::JUSTICIER => array(
            Options_Roles_Enum::JUSTICIER_NOMBRE_DE_TIRS => 1
        ),
        Roles_Enum::VEILLEUR => array(
            Options_Roles_Enum::VEILLEUR_IGNORE_IMMUNITE_DETECTION => 1,
            Options_Roles_Enum::VEILLEUR_PEUT_SE_CIBLER => 0
        ),
        Roles_Enum::VETERAN => array(
            Options_Roles_Enum::VETERAN_EXCLU_ALEATOIRE => 0,
            Options_Roles_Enum::VETERAN_LIMITE_A_X_NUITS => 2,
            Options_Roles_Enum::VETERAN_IGNORE_INVULNERABLE => 0
        ),
        Roles_Enum::MAIRE => array(
            Options_Roles_Enum::MAIRE_PERD_VOTE_SUPP_SI_CULTE => 0,
            Options_Roles_Enum::MAIRE_VOTES_COMPTENT_POUR_X => 3,
            Options_Roles_Enum::MAIRE_INSOIGNABLE_APRES_DEVO => 1
        ),
        Roles_Enum::GARDIEN_DE_PRISON => array(
            Options_Roles_Enum::GARDIEN_PEUT_FAIRE_X_EXECUTIONS => 1
        ),
        Roles_Enum::MEDECIN_LEGISTE => array(
            Options_Roles_Enum::MEDECIN_ECHOUE_JAMAIS => 1,
            Options_Roles_Enum::MEDECIN_DECOUVRE_DERNIERES_VOLONTES => 1,
            Options_Roles_Enum::MEDECIN_DECOUVRE_DEATH_TYPE => 1
        ),
        Roles_Enum::MACON => array(
            Options_Roles_Enum::MACON_DEVIENT_CHEF_QUAND_SEUl => 0
        ),
        Roles_Enum::MACON_CHEF => array(
            Options_Roles_Enum::CHEF_MACON_EXCLU_ROLE_ALEATOIRE => 1,
            Options_Roles_Enum::CHEF_MACON_NOMBRE_RECRUTEMENT => 2
        )





    );



    public static function getOptions($role)
    {
        return self::$options[$role];
    }
} 