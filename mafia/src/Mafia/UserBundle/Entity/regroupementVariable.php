<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 21/10/2015
 * Time: 14:20
 */
namespace Mafia\UserBundle\Entity;

abstract class regroupementVariable {

    /* Nombre de contact dont les MP sont affichés par page */
    const NB_MP_PAR_PAGE = 20;
    /* Nbr de bannissement sur la page des bans de tel user */
    const NB_BANS_PAR_PAGE_USER = 5;
    /* Nbr de bannissement sur la page de tous les bans */
    const NB_BANS_PAR_PAGE_TOUT = 5;
    /* Nbr de bannissement sur la page de moderation */
    const NB_DERNIERS_BANS = 5;
    /* Nbr de contacts sur la page de messages */
    const NB_CONTACTS_PAR_PAGE = 20;
} 