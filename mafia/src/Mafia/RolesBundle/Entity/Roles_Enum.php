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
abstract class Roles_Enum {

    // VILLE \\
    const SHERRIF = 0;
    const INSPECTEUR = 1;
    const DOCTEUR = 2;
    const GARDE_DU_CORPS = 3;
    const VIGIL = 4;
    const CITOYEN = 5;
    const CONDUCTEUR_DE_BUS = 6;
    const CORONER = 7;
    const DETECTIVE = 8;
    const ESCORTEUR = 9;
    const MACON = 10;
    const MACON_CHEF = 11;
    const GARDIEN_DE_PRISON = 12;
    const LOOKOUT = 13;

    // MAFIA \\
    const PARRAIN = 14;
    const CONSEILLER = 15;
    const FAUSSAIRE = 16;
    const MAFIOSO = 17;
    const MAITRE_CHANTEUR = 18;
    const CONCIERGE = 19;
    const CONSORT = 20;

    // NEUTRE \\
    const TUEUR_EN_SERIE = 21;
    const SURVIVALISTE = 22;
    const PYROMANE = 23;
    const MARIONNETISTE = 24;
    const AMNESIQUE = 25;
    const CULTE = 26;
    const EXECUTEUR = 27;
    const JESTER = 28;
} 