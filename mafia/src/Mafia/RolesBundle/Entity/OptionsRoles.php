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
                    OptionsRolesEnum::PARRAIN_INVULNERABLE_LA_NUIT => "Invulnérable la nuit",
                    OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_DETECTIONS => "Immunisé contre les détections",
                    OptionsRolesEnum::PARRAIN_IMMUNISE_CONTRE_BLOCAGE => "Immunisé contre les blocages",
                    OptionsRolesEnum::PARRAIN_PEUT_TUER_MAFIOSO => "Peut tuer sans Mafioso"
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
        }
        return array();
    }

    public static function getValeursPossibles($role,$option)
    {
        $resultat = array("min"=>false,"max"=>true);
        switch($role)
        {
            case RolesEnum::MARSHALL :
                switch($option)
                {
                    case OptionsRolesEnum::MARSHALL_NOMBRE_LYNCHAGE :
                        $resultat = array("min"=>1,"max"=>2);
                        break;
                    case OptionsRolesEnum::MARSHALL_NOMBRE_MORT_PAR_LYNCHAGE :
                        $resultat = array("min"=>1,"max"=>2);
                        break;
                    default :
                        break;
                }
                break;
            case RolesEnum::JUSTICIER :
                if ($option == OptionsRolesEnum::JUSTICIER_NOMBRE_DE_TIRS) $resultat = array("min"=>1,"max"=>4);
                break;
            case RolesEnum::VETERAN :
                if ($option == OptionsRolesEnum::VETERAN_LIMITE_A_X_NUITS ) $resultat = array("min"=>2,"max"=>3);
                break;
            case RolesEnum::MAIRE :
                if ($option == OptionsRolesEnum::MAIRE_VOTES_COMPTENT_POUR_X) $resultat = array("min"=>3,"max"=>4);
                break;
            case RolesEnum::GARDIEN_DE_PRISON :
                if ($option == OptionsRolesEnum::GARDIEN_PEUT_FAIRE_X_EXECUTIONS) $resultat = array("min"=>1,"max"=>3);
                break;
            case RolesEnum::MACON_CHEF :
                if ($option == OptionsRolesEnum::CHEF_MACON_NOMBRE_RECRUTEMENT) $resultat = array("min"=>2,"max"=>4);
                break;
            case RolesEnum::AGENT :
                if ($option == OptionsRolesEnum::AGENT_NOMBRE_NUITS_ENTRE_PISTAGE) $resultat = array("min"=>1,"max"=>2);
                break;
            case RolesEnum::PILLEUR :
                if ($option == OptionsRolesEnum::PILLEUR_PEUT_SE_CACHER_X_FOIS) $resultat = array("min"=>2,"max"=>4);
                break;
            case RolesEnum::CONCIERGE :
                if ($option == OptionsRolesEnum::CONCIERGE_NOMBRE_MASQUAGES) $resultat = array("min"=>1,"max"=>3);
                break;
        }
        return $resultat;
    }


} 