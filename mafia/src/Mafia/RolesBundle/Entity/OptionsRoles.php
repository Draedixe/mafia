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
            OptionsRolesEnum::PARRAIN_PEUT_TUER_SANS_MAFIOSO => 1
        ),
        RolesEnum::CONCIERGE => array(
            OptionsRolesEnum::CONCIERGE_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::CONCIERGE_NOMBRE_MASQUAGES => 2
        ),
        RolesEnum::KIDNAPPER => array(
            OptionsRolesEnum::KIDNAPPER_DEVIENT_MAFIOSO_QUAND_SEUL => 1,
            OptionsRolesEnum::KIDNAPPER_PEUT_EMPRISONNER_MEMBRE_MAFIA => 0
        ),
        RolesEnum::ADMINISTRATEUR => array(
            OptionsRolesEnum::ADMINISTRATEUR_REMPLACE_DRAGON => 0,
            OptionsRolesEnum::ADMINISTRATEUR_DETECTE_ROLE_EXACT => 0,
            OptionsRolesEnum::ADMINISTRATEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1
        ),
        RolesEnum::IMPOSTEUR => array(
            OptionsRolesEnum::IMPOSTEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::IMPOSTEUR_PEUT_SE_CACHER_X_FOIS => 2,
            OptionsRolesEnum::IMPOSTEUR_CIBLE_ALERTEE => 0,
            OptionsRolesEnum::IMPOSTEUR_PEUT_SE_CACHER_CHEZ_TRIADE => 0
        ),
        RolesEnum::DRAGON => array(
            OptionsRolesEnum::DRAGON_INVULNERABLE_LA_NUIT => 1,
            OptionsRolesEnum::DRAGON_IMMUNISE_CONTRE_DETECTIONS => 1,
            OptionsRolesEnum::DRAGON_IMMUNISE_CONTRE_BLOCAGE => 0,
            OptionsRolesEnum::DRAGON_PEUT_TUER_SANS_TUEUR_A_GAGE => 1
        ),
        RolesEnum::FAUSSAIRE => array(
            OptionsRolesEnum::FAUSSAIRE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::FAUSSAIRE_IMMUNISE_DETECTION => 0
        ),
        RolesEnum::MAITRE_ENCENS => array(
            OptionsRolesEnum::MAITRE_ENCENS_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::MAITRE_ENCENS_NOMBRE_MASQUAGES => 2
        ),
        RolesEnum::INFORMATEUR => array(
            OptionsRolesEnum::INFORMATEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::INFORMATEUR_MASQUE_ROLE_VICTIME =>0
        ),
        RolesEnum::INTERROGATEUR => array(
            OptionsRolesEnum::INTERROGATEUR_PEUT_FAIRE_X_EXECUTIONS => 1
        ),
        RolesEnum::LIAISON => array(
            OptionsRolesEnum::LIAISON_DEVIENT_TUEUR_A_GAGE_QUAND_SEULE => 1,
            OptionsRolesEnum::LIAISON_IMBLOCABLE => 0,
            OptionsRolesEnum::LIAISON_DETECTE_CIBLE_IMBLOCABLE => 0
        ),
        RolesEnum::MAITRE_SILENCE => array(
            OptionsRolesEnum::MAITRE_SILENCE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::MAITRE_SILENCE_VICTIME_PEUT_PARLER_DURANT_JUGEMENT => 0
        ),
        RolesEnum::AVANT_GARDE => array(
            OptionsRolesEnum::AVANT_GARDE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => 1,
            OptionsRolesEnum::AVANT_GARDE_NOMBRE_NUITS_ENTRE_PISTAGE => 1
        ),
        RolesEnum::AMNESIQUE => array(
            OptionsRolesEnum::AMNESIQUE_NOUVEAU_ROLE_REVELE => 1,
            OptionsRolesEnum::AMNESIQUE_INAFFILIABLE_VILLE => 0,
            OptionsRolesEnum::AMNESIQUE_INAFFILIABLE_MAFIA_TRIADE => 0,
            OptionsRolesEnum::AMNESIQUE_NON_ROLE_TUEUR => 0
        ),
        RolesEnum::PYROMANE => array(
            OptionsRolesEnum::PYROMANE_INCENDIE_TUE_CIBLE_VICTIME => 1,
            OptionsRolesEnum::PYROMANE_INCENDIE_TUE_TOUJOURS => 0,
            OptionsRolesEnum::PYROMANE_INVULNERABLE_NUIT => 1,
            OptionsRolesEnum::PYROMANE_VICTIME_SAIT_MAISON_ARROSEE_ESSENCE => 1,
            OptionsRolesEnum::PYROMANE_ARROSE_BLOQUEURS_ROLES => 0
        ),
        RolesEnum::AUDITEUR => array(
            OptionsRolesEnum::AUDITEUR_CONVERTIT_MAFIA_MAFIOSO => 1,
            OptionsRolesEnum::AUDITEUR_CONVERTIT_TRIADE_TUEUR_A_GAGE => 1,
            OptionsRolesEnum::AUDITEUR_SI_CIBLE_INVULNERABLE_NUIT_ALORS_NON_CONVERTIBLE => 1,
            OptionsRolesEnum::AUDITEUR_LIMITATION_CONVERSIONS => 3
        ),
        RolesEnum::CULTISTE => array(
            OptionsRolesEnum::CULTISTE_SI_CIBLE_INVULNERABLE_NUIT_ALORS_NON_CONVERTIBLE => 1,
            OptionsRolesEnum::CULTISTE_NUITS_ENTRE_CONVERSION => 1
        ),
        RolesEnum::BOURREAU => array(
            OptionsRolesEnum::BOURREAU_DEVIENT_BOUFFON_APRES_ECHEC => 1,
            OptionsRolesEnum::BOURREAU_CIBLE_TOUJOURS_VILLE => 1,
            OptionsRolesEnum::BOURREAU_DOIT_SURVIVRE_JUSQUA_FIN => 0,
            OptionsRolesEnum::BOURREAU_INVULNERABLE_NUIT => 0
        ),
        RolesEnum::BOUFFON => array(
            OptionsRolesEnum::BOUFFON_UN_DES_JOUEURS_AYANT_VOTE_MOURRA => 1
        ),
        RolesEnum::JUGE => array(
            OptionsRolesEnum::JUGE_DEUX_COUR_APPEL_AUTORISEES => 0,
            OptionsRolesEnum::JUGE_UN_JOUR_ENTRE_COUR_APPEL => 0,
            OptionsRolesEnum::JUGE_VOTE_COMPTE_POUR => 3
        ),
        RolesEnum::TUEUR_DE_MASSE => array(
            OptionsRolesEnum::TUEUR_MASSE_INVULNERABLE_NUIT => 1,
            OptionsRolesEnum::TUEUR_MASSE_PEUT_SE_CIBLER => 1,
            OptionsRolesEnum::TUEUR_MASSE_TEMPS_RECHARGE => 1,
            OptionsRolesEnum::TUEUR_MASSE_IMMUNISE_DETECTION => 0
        ),
        RolesEnum::TUEUR_EN_SERIE => array(
            OptionsRolesEnum::TUEUR_SERIE_INVULNERABLE_NUIT => 1,
            OptionsRolesEnum::TUEUR_SERIE_TUE_BLOQUEUR_ROLE => 0,
            OptionsRolesEnum::TUEUR_SERIE_GAGNE_EGALITE_CONTRE_PYROMANE => 0,
            OptionsRolesEnum::TUEUR_SERIE_IMMUNISER_DETECTION => 0
        ),
        RolesEnum::SURVIVANT => array(
            OptionsRolesEnum::SURVIVANT_NOMBRE_GILET_PARE_BALLE => 3
        ),
        RolesEnum::MARIONETTISTE => array(
            OptionsRolesEnum::MARIONETTISTE_PEUT_SE_RENDRE_CIBLE => 1,
            OptionsRolesEnum::MARIONETTISTE_VICTIME_SAIT_CONTROLE => 1,
            OptionsRolesEnum::MARIONETTISTE_TRANSFORME_SORCIER_SI_CONVERTI => 1
        ),
        RolesEnum::GOUROU => array(
            OptionsRolesEnum::SORCIER_LIMITE_SAUVETAGE => 2,
            OptionsRolesEnum::SORCIER_NUIT_ECART_ENTRE_SAUVETAGE => 0
        )


    );


    public static function getOptions($role)
    {
        return self::$options[$role];
    }

    public static function getOptionsDefaut($role,$option)
    {
        return self::$options[$role][$option];
    }

    public static function getName($role)
    {
        switch($role)
        {
            case RolesEnum::SHERIFF :
                return array(
                    OptionsRolesEnum::SHERIFF_DETECT_MAFIA_TRIADE => "Détecte la mafia et la triade",
                    OptionsRolesEnum::SHERIFF_DETECT_TUEUR_EN_SERIE => "Détecte le tueur en série",
                    OptionsRolesEnum::SHERIFF_DETECT_PYROMANE => "Détecte le pyromane",
                    OptionsRolesEnum::SHERIFF_DETECT_CULTE => "Détecte le culte",
                    OptionsRolesEnum::SHERIFF_DETECT_TUEUR_DE_MASSE => "Détecte le tueur de masse"
                );
                break;
            case RolesEnum::CITOYEN :
                return array(
                    OptionsRolesEnum::CITOYEN_GILET_PARE_BALLE => "Gilet pare-balles à usage unique ",
                    OptionsRolesEnum::CITOYEN_VICTOIRE_EGALITE_MAFIA => "Victoire en cas d'égalité avec la mafia"
                );
                break;
            case RolesEnum::DOCTEUR :
                return array(
                    OptionsRolesEnum::DOCTEUR_SAIT_CIBLE_ATTAQUEE => "Sait si la cible est attaquée",
                    OptionsRolesEnum::DOCTEUR_EMPECHE_CONVERSION_CULTE => "Empèche la conversion au culte",
                    OptionsRolesEnum::DOCTEUR_SAIT_CONVERTI_CULTE => "Sait si la cible est convertie au culte",
                    OptionsRolesEnum::DOCTEUR_DEVIENT_SORCIER_SI_CULTE => "Devient sorcier quand converti au culte"
                );
                break;
            case RolesEnum::MARSHALL :
                return array(
                    OptionsRolesEnum::MARSHALL_NOMBRE_LYNCHAGE => "Nombre d'exécutions de groupe autorisées",
                    OptionsRolesEnum::MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE => "Nombre d'exécutions par exécution de groupe"
                );
                break;
            case RolesEnum::GARDE_DU_CORPS :
                return array(
                    OptionsRolesEnum::GARDE_IGNORE_INVULNERABLE => "Ignore l'invulnérabilité",
                    OptionsRolesEnum::GARDE_INSOIGNABLE => "Ne pas pas être soigné",
                    OptionsRolesEnum::GARDE_NON_CONVERSION_CULTE => "Empêche la conversion au Culte"
                );
                break;
            case RolesEnum::INSPECTEUR :
                return array(
                    OptionsRolesEnum::INSPECTEUR_DECOUVRE_ROLE_EXACT => "Découvre le rôle exact"
                );
                break;
            case RolesEnum::DETECTIVE :
                return array(
                    OptionsRolesEnum::DETECTIVE_IGNORE_IMMUNITE_DETECTION => "Ignore l'immunité aux détections"
                );
                break;
            case RolesEnum::ESCORT :
                return array(
                    OptionsRolesEnum::ESCORT_INBLOCABLE => "Le rôle ne peut pas être bloqué",
                    OptionsRolesEnum::ESCORT_DETECTE_INSENSIBLE_BLOCAGE => "Détecte les cibles insensibles au blocage"
                );
                break;
            case RolesEnum::ESPION :
                return array(
                    OptionsRolesEnum::ESPION_VOIT_CIBLE_MAFIA_TRIADE => "Voit la cible de la Mafia/Triade",
                    OptionsRolesEnum::ESPION_VOIT_MEURTRE_MAFIA_TRIADE => "Voit les meurtres de la Mafia/Triade"
                );
                break;
            case RolesEnum::JUSTICIER :
                return array(
                    OptionsRolesEnum::JUSTICIER_NOMBRE_DE_TIRS => "Nombre de tirs"
                );
                break;
            case RolesEnum::VEILLEUR :
                return array(
                    OptionsRolesEnum::VEILLEUR_IGNORE_IMMUNITE_DETECTION => "Ignore l'immunité à la détection",
                    OptionsRolesEnum::VEILLEUR_PEUT_SE_CIBLER => "Peut se cibler lui-même"
                );
                break;
            case RolesEnum::VETERAN :
                return array(
                    OptionsRolesEnum::VETERAN_LIMITE_A_X_NUITS => "Limité à combien de nuits",
                    OptionsRolesEnum::VETERAN_IGNORE_INVULNERABLE => "Ignore l'invulnérabilité"
                );
                break;
            case RolesEnum::MAIRE :
                return array(
                    OptionsRolesEnum::MAIRE_PERD_VOTE_SUPP_SI_CULTE => "Perd ses votes supplémentaire si dans le Culte",
                    OptionsRolesEnum::MAIRE_VOTES_COMPTENT_POUR_X => "Votes comptent pour",
                    OptionsRolesEnum::MAIRE_INSOIGNABLE_APRES_DEVO => "Insoignable après dévoilement"
                );
                break;
            case RolesEnum::GARDIEN_DE_PRISON :
                return array(
                    OptionsRolesEnum::GARDIEN_PEUT_FAIRE_X_EXECUTIONS => "Nombre d'exécutions autorisées"
                );
                break;
            case RolesEnum::MEDECIN_LEGISTE :
                return array(
                    OptionsRolesEnum::MEDECIN_ECHOUE_JAMAIS => "Découvre toutes les cibles (n'échoue jamais)",
                    OptionsRolesEnum::MEDECIN_DECOUVRE_DERNIERES_VOLONTES => "Découvre les dernières volontés",
                    OptionsRolesEnum::MEDECIN_DECOUVRE_DEATH_TYPE => "Découvre la Death Type"
                );
                break;
            case RolesEnum::MACON :
                return array(
                    OptionsRolesEnum::MACON_DEVIENT_CHEF_QUAND_SEUl => "Devient Chef des Maçons quand il est seul"
                );
                break;
            case RolesEnum::MACON_CHEF :
                return array(
                    OptionsRolesEnum::CHEF_MACON_NOMBRE_RECRUTEMENT => "Nombre de recrutements autorises"
                );
                break;
            case RolesEnum::AGENT :
                return array(
                    OptionsRolesEnum::AGENT_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::AGENT_NOMBRE_NUITS_ENTRE_PISTAGE => "Nombre de nuits entres 2 pistages"
                );
                break;
            case RolesEnum::PILLEUR :
                return array(
                    OptionsRolesEnum::PILLEUR_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_X_FOIS => "Nombre de fois qu'il peut se cacher",
                    OptionsRolesEnum::PILLEUR_CIBLE_ALERTEE => "La cible est alertée",
                    OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_CHEZ_MAFIEUX => "Peut se cacher chez un Mafieux"
                );
                break;
            case RolesEnum::MAITRE_CHANTEUR :
                return array(
                    OptionsRolesEnum::MAITRE_CHANTEUR_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::MAITRE_CHANTEUR_VICTIME_PEUT_PARLER_DURANT_JUGEMENT => "La victime peut parler pendant le jugement"
                );
                break;
            case RolesEnum::CONSEILLER :
                return array(
                    OptionsRolesEnum::CONSEILLER_REMPLACE_PARRAIN => "Remplace le Parrain",
                    OptionsRolesEnum::CONSEILLER_DETECTE_ROLE_EXACT => "Détecte le rôle exact",
                    OptionsRolesEnum::CONSEILLER_DEVIENT_MAFIOSO_QUAND_SEUL => "Devient un Mafioso quand seul"
                );
                break;
            case RolesEnum::PROSTITUEE :
                return array(
                    OptionsRolesEnum::PROSTITUEE_DEVIENT_MAFIOSO_QUAND_SEULE => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::PROSTITUEE_IMBLOCABLE => "Ne peut pas être bloquée",
                    OptionsRolesEnum::PROSTITUEE_DETECTE_CIBLE_IMBLOCABLE => "Détecte les cibles immunisée au blocage"
                );
                break;
            case RolesEnum::MAITRE_DU_DEGUISEMENT :
                return array(
                    OptionsRolesEnum::MAITRE_DEGUISEMENT_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::MAITRE_DEGUISEMENT_MASQUE_ROLE_VICTIME => "Masque le rôle de la victime"
                );
                break;
            case RolesEnum::CONTREFACTEUR :
                return array(
                    OptionsRolesEnum::CONTREFACTEUR_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::CONTREFACTEUR_IMMUNISE_DETECTION => "Immunisé contre les détections"
                );
                break;
            case RolesEnum::PARRAIN :
                return array(
                    OptionsRolesEnum::PARRAIN_INVULNERABLE_LA_NUIT => "Invulnévrable la nuit",
                    OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_DETECTIONS => "Immunisé contre les détections",
                    OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_BLOCAGE => "Immunisé contre les blocages",
                    OptionsRolesEnum::PARRAIN_PEUT_TUER_SANS_MAFIOSO => "Peut tuer sans Mafioso"
                );
                break;
            case RolesEnum::CONCIERGE :
                return array(
                    OptionsRolesEnum::CONCIERGE_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::CONCIERGE_NOMBRE_MASQUAGES => "Nombre de masquages",
                );
                break;
            case RolesEnum::KIDNAPPER :
                return array(
                    OptionsRolesEnum::KIDNAPPER_DEVIENT_MAFIOSO_QUAND_SEUL => "Deviens Mafioso quand seul",
                    OptionsRolesEnum::KIDNAPPER_PEUT_EMPRISONNER_MEMBRE_MAFIA => "Peut emprisonner des membres de la Mafia",
                );
                break;
            case RolesEnum::ADMINISTRATEUR :
                return array(
                    OptionsRolesEnum::ADMINISTRATEUR_REMPLACE_DRAGON => "Remplace le Dragon",
                    OptionsRolesEnum::ADMINISTRATEUR_DETECTE_ROLE_EXACT => "Détecte le rôle exact",
                    OptionsRolesEnum::ADMINISTRATEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Devient tueur à gage quand seul"
                );
                break;
            case RolesEnum::IMPOSTEUR :
                return array(
                    OptionsRolesEnum::IMPOSTEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::IMPOSTEUR_PEUT_SE_CACHER_X_FOIS => "Nombre de fois qu'il peut se cacher",
                    OptionsRolesEnum::IMPOSTEUR_CIBLE_ALERTEE => "La cible est alertée",
                    OptionsRolesEnum::IMPOSTEUR_PEUT_SE_CACHER_CHEZ_TRIADE => "Peut se cacher chez un membre de la Triade"
                );
                break;
            case RolesEnum::DRAGON :
                return array(
                    OptionsRolesEnum::DRAGON_INVULNERABLE_LA_NUIT => "Invulnévrable la nuit",
                    OptionsRolesEnum::DRAGON_IMMUNISE_CONTRE_DETECTIONS => "Immunisé contre les détections",
                    OptionsRolesEnum::DRAGON_IMMUNISE_CONTRE_BLOCAGE => "Immunisé contre les blocages",
                    OptionsRolesEnum::DRAGON_PEUT_TUER_SANS_TUEUR_A_GAGE => "Peut tuer sans tueur à gage"
                );
                break;
            case RolesEnum::FAUSSAIRE :
                return array(
                    OptionsRolesEnum::FAUSSAIRE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::FAUSSAIRE_IMMUNISE_DETECTION => "Immunisé contre les détections"
                );
                break;
            case RolesEnum::MAITRE_ENCENS :
                return array(
                    OptionsRolesEnum::MAITRE_ENCENS_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::MAITRE_ENCENS_NOMBRE_MASQUAGES => "Nombre de masquages",
                );
                break;
            case RolesEnum::INFORMATEUR :
                return array(
                    OptionsRolesEnum::INFORMATEUR_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::INFORMATEUR_MASQUE_ROLE_VICTIME => "Masque le rôle de la victime"
                );
                break;
            case RolesEnum::INTERROGATEUR :
                return array(
                    OptionsRolesEnum::INTERROGATEUR_PEUT_FAIRE_X_EXECUTIONS => "Nombre d'exécutions autorisées"
                );
                break;
            case RolesEnum::LIAISON :
                return array(
                    OptionsRolesEnum::LIAISON_DEVIENT_TUEUR_A_GAGE_QUAND_SEULE => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::LIAISON_IMBLOCABLE => "Ne peut pas être bloquée",
                    OptionsRolesEnum::LIAISON_DETECTE_CIBLE_IMBLOCABLE => "Détecte les cibles immunisée au blocage"
                );
                break;
            case RolesEnum::MAITRE_SILENCE :
                return array(
                    OptionsRolesEnum::MAITRE_SILENCE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::MAITRE_SILENCE_VICTIME_PEUT_PARLER_DURANT_JUGEMENT => "La victime peut parler pendant le jugement"
                );
                break;
            case RolesEnum::AVANT_GARDE :
                return array(
                    OptionsRolesEnum::AVANT_GARDE_DEVIENT_TUEUR_A_GAGE_QUAND_SEUL => "Deviens tueur à gage quand seul",
                    OptionsRolesEnum::AVANT_GARDE_NOMBRE_NUITS_ENTRE_PISTAGE => "Nombre de nuits entres 2 pistages"
                );
                break;
            case RolesEnum::AMNESIQUE :
                return array(
                    OptionsRolesEnum::AMNESIQUE_NOUVEAU_ROLE_REVELE => "Le nouveau rôle est révélé à la ville",
                    OptionsRolesEnum::AMNESIQUE_INAFFILIABLE_VILLE => "Ne peut pas s'affilier à la Ville",
                    OptionsRolesEnum::AMNESIQUE_INAFFILIABLE_MAFIA_TRIADE => "Ne peut pas s'affilier à la Mafia/Triade",
                    OptionsRolesEnum::AMNESIQUE_NON_ROLE_TUEUR => "Ne peut pas devenir un rôle tueur"
                );
                break;
            case RolesEnum::PYROMANE :
                return array(
                    OptionsRolesEnum::PYROMANE_INCENDIE_TUE_CIBLE_VICTIME => "L'incendie tue la cible de sa victime",
                    OptionsRolesEnum::PYROMANE_INCENDIE_TUE_TOUJOURS => "L'incendie tue toujours",
                    OptionsRolesEnum::PYROMANE_INVULNERABLE_NUIT => "Invulnérable la nuit",
                    OptionsRolesEnum::PYROMANE_VICTIME_SAIT_MAISON_ARROSEE_ESSENCE => "La victime sait que sa maison a été arrosé d'essence",
                    OptionsRolesEnum::PYROMANE_ARROSE_BLOQUEURS_ROLES => "Arrose les bloqueurs de rôles"
                );
                break;
            case RolesEnum::AUDITEUR :
                return array(
                    OptionsRolesEnum::AUDITEUR_CONVERTIT_MAFIA_MAFIOSO => "Convertit la mafia en mafioso",
                    OptionsRolesEnum::AUDITEUR_CONVERTIT_TRIADE_TUEUR_A_GAGE => "Convertit la triade en tueur à gage",
                    OptionsRolesEnum::AUDITEUR_SI_CIBLE_INVULNERABLE_NUIT_ALORS_NON_CONVERTIBLE => "Si la cible ne peut pas être tuée la nuit, elle ne peut pas être convertie",
                    OptionsRolesEnum::AUDITEUR_LIMITATION_CONVERSIONS => "Limitation de conversions"
                );
                break;
            case RolesEnum::CULTISTE :
                return array(
                    OptionsRolesEnum::CULTISTE_SI_CIBLE_INVULNERABLE_NUIT_ALORS_NON_CONVERTIBLE => "Si la cible ne peut pas être tuée la nuit, elle ne peut pas être convertie",
                    OptionsRolesEnum::CULTISTE_NUITS_ENTRE_CONVERSION => "Nombre de nuits entre chaque conversion"
                );
                break;
            case RolesEnum::BOURREAU :
                return array(
                    OptionsRolesEnum::BOURREAU_DEVIENT_BOUFFON_APRES_ECHEC => "Devenir un bouffon après un échec",
                    OptionsRolesEnum::BOURREAU_CIBLE_TOUJOURS_VILLE => "La cible est toujours de la ville",
                    OptionsRolesEnum::BOURREAU_DOIT_SURVIVRE_JUSQUA_FIN => "Doit survivre jusqu'à la fin",
                    OptionsRolesEnum::BOURREAU_INVULNERABLE_NUIT => "Invulnérable la nuit"
                );
                break;
            case RolesEnum::BOUFFON :
                return array(
                    OptionsRolesEnum::BOUFFON_UN_DES_JOUEURS_AYANT_VOTE_MOURRA => "Un joueur aléatoire ayant voté coupable mourra"
                );
                break;
            case RolesEnum::JUGE :
                return array(
                    OptionsRolesEnum::JUGE_DEUX_COUR_APPEL_AUTORISEES => "2 Cour d'appel autorisées",
                    OptionsRolesEnum::JUGE_UN_JOUR_ENTRE_COUR_APPEL => "1 jour entre 2 cour d'appel",
                    OptionsRolesEnum::JUGE_VOTE_COMPTE_POUR => "Vote compte pour "
                );
                break;
            case RolesEnum::TUEUR_DE_MASSE :
                return array(
                    OptionsRolesEnum::TUEUR_MASSE_INVULNERABLE_NUIT => "Invulnérable la nuit",
                    OptionsRolesEnum::TUEUR_MASSE_PEUT_SE_CIBLER => "Peut se cible lui-même",
                    OptionsRolesEnum::TUEUR_MASSE_TEMPS_RECHARGE => "Temps de recharge",
                    OptionsRolesEnum::TUEUR_MASSE_IMMUNISE_DETECTION => "Immunisé à la détection"
                );
                break;
            case RolesEnum::TUEUR_EN_SERIE :
                return array(
                    OptionsRolesEnum::TUEUR_SERIE_INVULNERABLE_NUIT => "Invulnérable la nuit",
                    OptionsRolesEnum::TUEUR_SERIE_TUE_BLOQUEUR_ROLE => "Tue les bloqueurs de rôle",
                    OptionsRolesEnum::TUEUR_SERIE_GAGNE_EGALITE_CONTRE_PYROMANE => "Gagne à égalité contre un pyromane",
                    OptionsRolesEnum::TUEUR_SERIE_IMMUNISER_DETECTION => "Immunisé à la détection"
                );
                break;
            case RolesEnum::SURVIVANT :
                return array(
                    OptionsRolesEnum::SURVIVANT_NOMBRE_GILET_PARE_BALLE => "Nombre de gilet pare-balle"
                );
                break;
            case RolesEnum::MARIONETTISTE :
                return array(
                    OptionsRolesEnum::MARIONETTISTE_PEUT_SE_RENDRE_CIBLE => "Peut se rendre cible de sa cible",
                    OptionsRolesEnum::MARIONETTISTE_VICTIME_SAIT_CONTROLE => "Les victimes savent qu'elles sont contrôlées",
                    OptionsRolesEnum::MARIONETTISTE_TRANSFORME_SORCIER_SI_CONVERTI => "Se transforme en sorcier quand converti"
                );
                break;
            case RolesEnum::GOUROU :
                return array(
                    OptionsRolesEnum::SORCIER_LIMITE_SAUVETAGE => "Limite de sauvetages",
                    OptionsRolesEnum::SORCIER_NUIT_ECART_ENTRE_SAUVETAGE => "Nombre de nuits entre 2 sauvetages"
                );
                break;
        }
        return array();
    }

    public static function getValeursPossibles($role,$option)
    {
        $resultat = array("min"=>0,"max"=>1);
        switch($role) {
            case RolesEnum::MARSHALL :
                switch ($option) {
                    case OptionsRolesEnum::MARSHALL_NOMBRE_LYNCHAGE :
                        $resultat = array("min" => 1, "max" => 2);
                        break;
                    case OptionsRolesEnum::MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE :
                        $resultat = array("min" => 1, "max" => 2);
                        break;
                    default :
                        break;
                }
                break;
            case RolesEnum::JUSTICIER :
                if ($option == OptionsRolesEnum::JUSTICIER_NOMBRE_DE_TIRS) $resultat = array("min" => 1, "max" => 4);
                break;
            case RolesEnum::VETERAN :
                if ($option == OptionsRolesEnum::VETERAN_LIMITE_A_X_NUITS) $resultat = array("min" => 2, "max" => 3);
                break;
            case RolesEnum::MAIRE :
                if ($option == OptionsRolesEnum::MAIRE_VOTES_COMPTENT_POUR_X) $resultat = array("min" => 3, "max" => 4);
                break;
            case RolesEnum::GARDIEN_DE_PRISON :
                if ($option == OptionsRolesEnum::GARDIEN_PEUT_FAIRE_X_EXECUTIONS) $resultat = array("min" => 1, "max" => 3);
                break;
            case RolesEnum::MACON_CHEF :
                if ($option == OptionsRolesEnum::CHEF_MACON_NOMBRE_RECRUTEMENT) $resultat = array("min" => 2, "max" => 4);
                break;
            case RolesEnum::AGENT :
                if ($option == OptionsRolesEnum::AGENT_NOMBRE_NUITS_ENTRE_PISTAGE) $resultat = array("min" => 1, "max" => 2);
                break;
            case RolesEnum::PILLEUR :
                if ($option == OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_X_FOIS) $resultat = array("min" => 2, "max" => 4);
                break;
            case RolesEnum::CONCIERGE :
                if ($option == OptionsRolesEnum::CONCIERGE_NOMBRE_MASQUAGES) $resultat = array("min" => 1, "max" => 3);
                break;
            case RolesEnum::IMPOSTEUR :
                if ($option == OptionsRolesEnum::IMPOSTEUR_PEUT_SE_CACHER_X_FOIS) $resultat = array("min" => 2, "max" => 4);
                break;
            case RolesEnum::MAITRE_ENCENS :
                if ($option == OptionsRolesEnum::MAITRE_ENCENS_NOMBRE_MASQUAGES) $resultat = array("min" => 1, "max" => 3);
                break;
            case RolesEnum::INTERROGATEUR :
                if ($option == OptionsRolesEnum::INTERROGATEUR_PEUT_FAIRE_X_EXECUTIONS) $resultat = array("min" => 1, "max" => 3);
                break;
            case RolesEnum::AVANT_GARDE :
                if ($option == OptionsRolesEnum::AVANT_GARDE_NOMBRE_NUITS_ENTRE_PISTAGE) $resultat = array("min" => 1, "max" => 2);
                break;
            case RolesEnum::AUDITEUR :
                if ($option == OptionsRolesEnum::AUDITEUR_LIMITATION_CONVERSIONS) $resultat = array("min" => 2, "max" => 3);
                break;
            case RolesEnum::CULTISTE :
                if ($option == OptionsRolesEnum::CULTISTE_NUITS_ENTRE_CONVERSION) $resultat = array("min" => 1, "max" => 2);
                break;
            case RolesEnum::JUGE :
                if ($option == OptionsRolesEnum::JUGE_VOTE_COMPTE_POUR) $resultat = array("min" => 2, "max" => 4);
                break;
            case RolesEnum::TUEUR_DE_MASSE :
                if ($option == OptionsRolesEnum::TUEUR_MASSE_TEMPS_RECHARGE) $resultat = array("min" => 0, "max" => 2);
                break;
            case RolesEnum::SURVIVANT :
                if ($option == OptionsRolesEnum::SURVIVANT_NOMBRE_GILET_PARE_BALLE) $resultat = array("min" => 0, "max" => 4);
                break;
            case RolesEnum::GOUROU :
                if ($option == OptionsRolesEnum::SORCIER_LIMITE_SAUVETAGE) $resultat = array("min" => 2, "max" => 3);
                break;

        }
        return $resultat;
    }


} 