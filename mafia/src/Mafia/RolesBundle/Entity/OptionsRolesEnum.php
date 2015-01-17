<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/01/2015
 * Time: 22:39
 */

namespace Mafia\RolesBundle\Entity;


abstract class OptionsRolesEnum {


    //-- VILLE --\\

    // SHERRIF \\

    const SHERIFF_DETECT_MAFIA_TRIADE = 0;
    const SHERIFF_DETECT_TUEUR_EN_SERIE = 1;
    const SHERIFF_DETECT_TUEUR_DE_MASSE = 2;
    const SHERIFF_DETECT_PYROMANE = 3;
    const SHERIFF_DETECT_CULTE = 4;

    // CITOYEN \\

    const CITOYEN_GILET_PARE_BALLE = 0;
    const CITOYEN_VICTOIRE_EGALITE_MAFIA = 1;

    // DOCTEUR \\

    const DOCTEUR_SAIT_CIBLE_ATTAQUEE = 0;
    const DOCTEUR_EMPECHE_CONVERSION_CULTE = 1;
    const DOCTEUR_SAIT_CONVERTI_CULTE = 2;
    const DOCTEUR_DEVIENT_SORCIER_SI_CULTE = 3;

    // MARSHALL \\

    const MARSHALL_NOMBRE_LYNCHAGE = 0;
    const MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE = 1;

    // CONDUCTEUR DE BUS \\

    const CONDUCTEUR_PEUT_SE_CIBLER = 0;

    // GARDE DU CORPS \\

    const GARDE_IGNORE_INVULNERABLE = 0;
    const GARDE_INSOIGNABLE = 1;
    const GARDE_NON_CONVERSION_CULTE = 2;

    // INSPECTEUR \\

    const INSPECTEUR_DECOUVRE_ROLE_EXACT = 0;

    // DETECTIVE \\

    const DETECTIVE_IGNORE_IMMUNITE_DETECTION = 0;

    // ESCORT \\

    const ESCORT_INBLOCABLE = 0;
    const ESCORT_DETECTE_INSENSIBLE_BLOCAGE = 1;

    // ESPION \\

    const ESPION_VOIT_CIBLE_MAFIA_TRIADE  = 0;
    const ESPION_VOIT_MEURTRE_MAFIA_TRIADE = 1;

    // JUSTICIER \\

    const JUSTICIER_NOMBRE_DE_TIRS = 0;

    // VEILLEUR \\

    const VEILLEUR_IGNORE_IMMUNITE_DETECTION = 0;
    const VEILLEUR_PEUT_SE_CIBLER = 1;

    // VETERAN \\

    const VETERAN_LIMITE_A_X_NUITS = 0;
    const VETERAN_IGNORE_INVULNERABLE = 1;

    // MAIRE \\

    const MAIRE_PERD_VOTE_SUPP_SI_CULTE = 0;
    const MAIRE_VOTES_COMPTENT_POUR_X = 1;
    const MAIRE_INSOIGNABLE_APRES_DEVO = 2;

    // GARDIEN DE PRISON \\

    const GARDIEN_PEUT_FAIRE_X_EXECUTIONS = 0;

    // MEDECIN LEGISTE \\

    const MEDECIN_ECHOUE_JAMAIS = 0;
    const MEDECIN_DECOUVRE_DERNIERES_VOLONTES = 1;
    const MEDECIN_DECOUVRE_DEATH_TYPE = 2;

    // FRANC MACON \\

    const MACON_DEVIENT_CHEF_QUAND_SEUl = 0;

    // CHEF MACON \\

    const CHEF_MACON_NOMBRE_RECRUTEMENT = 0;

    //-- MAFIA --\\

    // AGENT \\

    const AGENT_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const AGENT_NOMBRE_NUITS_ENTRE_PISTAGE = 1;

    // PILLEUR \\

    const PILLEUR_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const PILLEUR_PEUT_SE_CACHER_X_FOIS = 1;
    const PILLEUR_CIBLE_ALERTEE = 2;
    const PILLEUR_PEUT_SE_CACHER_CHEZ_MAFIEUX = 3;

    // MAITRE CHANTEUR \\

    const MAITRE_CHANTEUR_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const MAITRE_CHANTEUR_VICTIME_PEUT_PARLER_DURANT_JUGEMENT = 1;

    // CONSEILLER \\

    const CONSEILLER_REMPLACE_PARRAIN = 0;
    const CONSEILLER_DETECTE_ROLE_EXACT = 1;
    const CONSEILLER_DEVIENT_MAFIOSO_QUAND_SEUL = 2;

    // PROSTITUEE \\

    const PROSTITUEE_DEVIENT_MAFIOSO_QUAND_SEULE = 0;
    const PROSTITUEE_IMBLOCABLE = 1;
    const PROSTITUEE_DETECTE_CIBLE_IMBLOCABLE = 2;

    // MAITRE_DEGUISEMENT \\

    const MAITRE_DEGUISEMENT_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const MAITRE_DEGUISEMENT_MASQUE_ROLE_VICTIME = 1;

    // CONTREFACTEUR \\

    const CONTREFACTEUR_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const CONTREFACTEUR_IMMUNISE_DETECTION = 1;

    // PARRAIN \\

    const PARRAIN_INVULNERABLE_LA_NUIT = 0;
    const PARRAIN_IMMUNISE_CONTRE_DETECTIONS = 1;
    const PARRAIN_IMMUNISE_CONTRE_BLOCAGE = 2;
    const PARRAIN_PEUT_TUER_MAFIOSO = 3;

    // CONCIERGE \\

    const CONCIERGE_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const CONCIERGE_NOMBRE_MASQUAGES = 1;

    // KIDNAPPER \\

    const KIDNAPPER_DEVIENT_MAFIOSO_QUAND_SEUL = 0;
    const KIDNAPPER_PEUT_EMPRISONNER_MEMBRE_MAFIA = 1;
}