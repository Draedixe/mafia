<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/01/2015
 * Time: 00:55
 */

namespace Mafia\RolesBundle\Entity;

abstract class OptionsRoles {

    private static $options = array(
        RolesEnum::SHERIFF =>  array(
            OptionsRolesEnum::SHERIFF_DETECT_MAFIA_TRIADE => 1,
            OptionsRolesEnum::SHERIFF_DETECT_TUEUR_EN_SERIE => 1,
            OptionsRolesEnum::SHERIFF_DETECT_PYROMANE => 1,
            OptionsRolesEnum::SHERIFF_DETECT_CULTE => 1,
            OptionsRolesEnum::SHERIFF_DETECT_TUEUR_DE_MASSE => 1
        ),
        RolesEnum::CITOYEN => array(
            OptionsRolesEnum::CITOYEN_GILET_PARE_BALLE => 1,
            OptionsRolesEnum::CITOYEN_VICTOIRE_EGALITE_MAFIA => 1
        ),
        RolesEnum::DOCTEUR => array(
            OptionsRolesEnum::DOCTEUR_SAIT_CIBLE_ATTAQUEE => 1,
            OptionsRolesEnum::DOCTEUR_EMPECHE_CONVERSION_CULTE => 0,
            OptionsRolesEnum::DOCTEUR_SAIT_CONVERTI_CULTE => 0,
            OptionsRolesEnum::DOCTEUR_DEVIENT_SORCIER_SI_CULTE => 0
        ),
        RolesEnum::MARSHALL => array(
            OptionsRolesEnum::MARSHALL_NOMBRE_LYNCHAGE => 1,
            OptionsRolesEnum::MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE => 3
        ),
        RolesEnum::CONDUCTEUR_DE_BUS => array(
            OptionsRolesEnum::CONDUCTEUR_PEUT_SE_CIBLER => 0
        ),
        RolesEnum::CRIEUR => array(
        ),
        RolesEnum::GARDE_DU_CORPS => array(
            OptionsRolesEnum::GARDE_IGNORE_INVULNERABLE => 1,
            OptionsRolesEnum::GARDE_INSOIGNABLE => 1,
            OptionsRolesEnum::GARDE_NON_CONVERSION_CULTE => 0
        ),
        RolesEnum::INSPECTEUR => array(
            OptionsRolesEnum::INSPECTEUR_DECOUVRE_ROLE_EXACT => 0
        ),
        RolesEnum::DETECTIVE => array(
            OptionsRolesEnum::DETECTIVE_IGNORE_IMMUNITE_DETECTION => 1
        ),
        RolesEnum::ESCORT => array(
            OptionsRolesEnum::ESCORT_INBLOCABLE => 0,
            OptionsRolesEnum::ESCORT_DETECTE_INSENSIBLE_BLOCAGE => 0
        ),
        RolesEnum::ESPION => array(
            OptionsRolesEnum::ESPION_VOIT_CIBLE_MAFIA_TRIADE => 0,
            OptionsRolesEnum::ESPION_VOIT_MEURTRE_MAFIA_TRIADE => 1
        ),
        RolesEnum::JUSTICIER => array(
            OptionsRolesEnum::JUSTICIER_NOMBRE_DE_TIRS => 1
        ),
        RolesEnum::VEILLEUR => array(
            OptionsRolesEnum::VEILLEUR_IGNORE_IMMUNITE_DETECTION => 1,
            OptionsRolesEnum::VEILLEUR_PEUT_SE_CIBLER => 0
        ),
        RolesEnum::VETERAN => array(
            OptionsRolesEnum::VETERAN_LIMITE_A_X_NUITS => 2,
            OptionsRolesEnum::VETERAN_IGNORE_INVULNERABLE => 0
        ),
        RolesEnum::MAIRE => array(
            OptionsRolesEnum::MAIRE_PERD_VOTE_SUPP_SI_CULTE => 0,
            OptionsRolesEnum::MAIRE_VOTES_COMPTENT_POUR_X => 3,
            OptionsRolesEnum::MAIRE_INSOIGNABLE_APRES_DEVO => 1
        ),
        RolesEnum::GARDIEN_DE_PRISON => array(
            OptionsRolesEnum::GARDIEN_PEUT_FAIRE_X_EXECUTIONS => 1
        ),
        RolesEnum::MEDECIN_LEGISTE => array(
            OptionsRolesEnum::MEDECIN_ECHOUE_JAMAIS => 1,
            OptionsRolesEnum::MEDECIN_DECOUVRE_DERNIERES_VOLONTES => 1,
            OptionsRolesEnum::MEDECIN_DECOUVRE_DEATH_TYPE => 1
        ),
        RolesEnum::MACON => array(
            OptionsRolesEnum::MACON_DEVIENT_CHEF_QUAND_SEUl => 0
        ),
        RolesEnum::MACON_CHEF => array(
            OptionsRolesEnum::CHEF_MACON_NOMBRE_RECRUTEMENT => 2
        ),
        RolesEnum::AGENT => array(
            OptionsRolesEnum::AGENT_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::AGENT_NOMBRE_NUITS_ENTRE_PISTAGE => 1
        ),
        RolesEnum::PILLEUR => array(
            OptionsRolesEnum::PILLEUR_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_X_FOIS => 2,
            OptionsRolesEnum::PILLEUR_CIBLE_ALERTEE => 0,
            OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_CHEZ_MAFIEUX => 0
        ),
        RolesEnum::MAITRE_CHANTEUR => array(
            OptionsRolesEnum::MAITRE_CHANTEUR_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::MAITRE_CHANTEUR_VICTIME_PEUT_PARLER_DURANT_JUGEMENT => 0
        ),
        RolesEnum::CONSEILLER => array(
            OptionsRolesEnum::CONSEILLER_REMPLACE_PARRAIN => 0,
            OptionsRolesEnum::CONSEILLER_DETECTE_ROLE_EXACT => 0,
            OptionsRolesEnum::CONSEILLER_DEVIENT_MAFIOSO_QUAND_SEUL => 1
        ),
        RolesEnum::PROSTITUEE => array(
            OptionsRolesEnum::PROSTITUEE_DEVIENT_MAFIOSO_QUAND_SEULE => 1,
            OptionsRolesEnum::PROSTITUEE_IMBLOCABLE => 0,
            OptionsRolesEnum::PROSTITUEE_DETECTE_CIBLE_IMBLOCABLE => 0
        ),
        RolesEnum::MAITRE_DU_DEGUISEMENT => array(
            OptionsRolesEnum::MAITRE_DEGUISEMENT_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::MAITRE_DEGUISEMENT_MASQUE_ROLE_VICTIME =>0
        ),
        RolesEnum::CONTREFACTEUR => array(
            OptionsRolesEnum::CONTREFACTEUR_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::CONTREFACTEUR_IMMUNISE_DETECTION => 0
        ),
        RolesEnum::PARRAIN => array(
            OptionsRolesEnum::PARRAIN_INVULNERABLE_LA_NUIT => 1,
            OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_DETECTIONS => 1,
            OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_BLOCAGE => 0,
            OptionsRolesEnum::PARRAIN_PEUT_TUER_MAFIOSO => 1
        ),
        RolesEnum::CONCIERGE => array(
            OptionsRolesEnum::CONCIERGE_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::CONCIERGE_NOMBRE_MASQUAGES => 2
        ),
        RolesEnum::KIDNAPPER => array(
            OptionsRolesEnum::KIDNAPPER_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::KIDNAPPER_PEUT_EMPRISONNER_MEMBRE_MAFIA => 0
        )








    );


    public static function getOptions($role)
    {
        return self::$options[$role];
    }
} 