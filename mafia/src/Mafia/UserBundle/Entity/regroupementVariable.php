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
    const NB_MP_PAR_PAGE = 1;
    /* Nbr de bannissement sur la page des bans de tel user */
    const NB_BANS_PAR_PAGE_USER = 1;
    /* Nbr de bannissement sur la page de tous les bans*/
    const NB_BANS_PAR_PAGE_TOUT = 1;
} 