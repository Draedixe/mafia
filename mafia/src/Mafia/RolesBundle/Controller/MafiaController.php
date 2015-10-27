<?php
/**
 * Created by PhpStorm.
 * User: Draedixe
 * Date: 20/10/2015
 * Time: 23:23
 */

namespace Mafia\RolesBundle\Controller;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\StatusEnum;
use Mafia\PartieBundle\Entity;
use Mafia\PartieBundle\Entity\Statut;
use Mafia\RolesBundle\Entity\FactionEnum;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MafiaController extends Controller {
    /**
     * Fonction appelée lorsque la mafia veut tuer quelqu'un
     * @param $idCible int L'id de la cible des mafioso
     * @return JsonResponse
     */
    public function mafiosoAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $repositoryStatut = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Statut');

        $ciblePartieCourant = $repositoryUser->find($idCible);
        $em = $this->getDoctrine()->getManager();
        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Mafioso ou parrain */
                                if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                    if($ciblePartieCourant->getRole()->getEnumFaction() != FactionEnum::MAFIA){

                                        $statut = $repositoryStatut->findOneBy(array("acteur"=>$userPartieCourant));
                                        /* Si le joueur n'avait pas décidé d'action avant */
                                        if($statut == null) {
                                            $statutTue = new Statut(StatusEnum::TUE, $ciblePartieCourant, $userPartieCourant);
                                            $em->persist($statutTue);
                                            $em->flush();
                                            return new JsonResponse(array("ACTION" => "OK"));
                                        } // Si il annule son action (il indique la même cible que précédemment)
                                        elseif ($statut->getVictime() == $ciblePartieCourant){
                                            $em->remove($statut);
                                            $em->flush();
                                            return new JsonResponse(array("ACTION" => "ANNULER"));
                                        } // Si il change de cible
                                        else {
                                            $statut->setVictime($ciblePartieCourant);
                                            $em->persist($statut);
                                            $em->flush();
                                            return new JsonResponse(array("ACTION" => "OK"));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return new JsonResponse(array("ACTION" => "ERREUR"));
    }

    /**
     * Fonction de l'agent
     * @param $idCible int L'id de la cible de l'agent
     */
    public function agentAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Tueur en série */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::AGENT) {
                                    if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                        $statut = new Statut(StatusEnum::DETECTE, $ciblePartieCourant, $userPartieCourant);
                                        $statut2 = new Statut(StatusEnum::OBSERVE, $ciblePartieCourant, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statut);
                                        $em->persist($statut2);
                                        $em->flush();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * Fonction du concierge
     * @param $idCible int L'id de la cible du concierge
     */
    public function conciergeAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien justicier */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::CONCIERGE) {
                                    if($userPartieCourant->getCapaciteRestante() > 0) {
                                        $statut = new Statut(StatusEnum::MASQUE, $ciblePartieCourant, $userPartieCourant);
                                        $userPartieCourant->setCapaciteRestante($userPartieCourant->getCapaciteRestante()-1);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statut);
                                        $em->persist($userPartieCourant);
                                        $em->flush();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * Fonction du conseiller
     * @param $idCible int L'id de la cible du conseiller
     */
    public function conseillerAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Tueur en série */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::AGENT) {
                                    if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                        $statut = new Statut(StatusEnum::INSPECTE, $ciblePartieCourant, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statut);
                                        $em->flush();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * Fonction du contrefacteur
     * @param $idCible int L'id de la cible du contrefacteur
     */
    public function contrefacteurAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Tueur en série */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::CONTREFACTEUR) {
                                    if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                        $statut = new Statut(StatusEnum::PIEGE, $ciblePartieCourant, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statut);
                                        $em->flush();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction du pilleur
     * @param $idCible int L'id de la cible du pilleur
     */
    public function pilleurAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Tueur en série */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PILLEUR) {
                                    if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                        if($userPartieCourant->getCapaciteRestante() > 0) {
                                            $statut = new Statut(StatusEnum::REDIRIGE, $ciblePartieCourant, $userPartieCourant);
                                            $userPartieCourant->setCapaciteRestante($userPartieCourant->getCapaciteRestante() - 1);
                                            $em = $this->getDoctrine()->getManager();
                                            $em->persist($statut);
                                            $em->persist($userPartieCourant);
                                            $em->flush();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction du maitre chanteur
     * @param $idCible int L'id de la cible du maitre chanteur
     */
    public function maitreChanteurAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien justicier */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::MAITRE_CHANTEUR) {
                                    $statut = new Statut(StatusEnum::FERME_LA, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statut);
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction de la prostituee
     * @param $idCible int L'id de la cible de la prostituee
     */
    public function prositueeAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien justicier */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PROSTITUEE) {
                                    $statut = new Statut(StatusEnum::BLOQUE, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statut);
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction du maitre du deguisement
     * @param $idCible int L'id de la cible du maitre du deguisement
     */
    public function maitreDuDeguisementAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien justicier */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::MAITRE_DU_DEGUISEMENT) {
                                    $statut = new Statut(StatusEnum::USURPE, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statut);
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}