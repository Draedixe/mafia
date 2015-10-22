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
use Mafia\PartieBundle\Entity\Statut;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VilleController extends Controller {

    /**
     * Fonction appelée lorsque le Citoyen veut s'habiller
     */
    public function citoyenAction(){

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si le joueur est en jeu */
        if($userPartieCourant != null){
            $partie = $userPartieCourant->getPartie();

            /* On vérifie que la partie existe bien */
            if($partie != null){

                /* Si c'est bien la nuit */
                if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                    /* S'il est bien vivant */
                    if ($userPartieCourant->getVivant()) {

                        /* Si le joueur a bien un rôle */
                        if ($userPartieCourant->getRole() != null) {

                            /* Si le joueur est bien Survivant */
                            if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::CITOYEN) {

                                /* S'il lui reste des Gilets */
                                if($userPartieCourant->getCapaciteRestante() > 0){
                                    $statutProtege = new Statut(StatusEnum::GILET,$userPartieCourant,$userPartieCourant);
                                    $userPartieCourant->setCapaciteRestante($userPartieCourant->getCapaciteRestante()-1);

                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutProtege);
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

    /**
     * Fonction appelée lorsque le veteran veut passer en alerte
     */
    public function veteranAction(){

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si le joueur est en jeu */
        if($userPartieCourant != null){
            $partie = $userPartieCourant->getPartie();

            /* On vérifie que la partie existe bien */
            if($partie != null){

                /* Si c'est bien la nuit */
                if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                    /* S'il est bien vivant */
                    if ($userPartieCourant->getVivant()) {

                        /* Si le joueur a bien un rôle */
                        if ($userPartieCourant->getRole() != null) {

                            /* Si le joueur est bien Survivant */
                            if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::VETERAN) {

                                /* S'il lui reste des Gilets */
                                if($userPartieCourant->getCapaciteRestante() > 0){
                                    $statutProtege = new Statut(StatusEnum::ALERTE,$userPartieCourant,$userPartieCourant);
                                    $userPartieCourant->setCapaciteRestante($userPartieCourant->getCapaciteRestante()-1);

                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutProtege);
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

    /**
     * Fonction du Conducteur de bus
     * @param $idCible int Id de la cible 1
     * @param $idCible2 int Id de la cible 2
     */
    public function conducteurDeBusAction($idCible,$idCible2){
        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);
        $ciblePartieCourant2 = $repositoryUser->find($idCible2);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 3 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $ciblePartieCourant2 != null  && $ciblePartieCourant != $ciblePartieCourant2){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();
            $partieCible2 = $ciblePartieCourant2->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null && $partieCible2 != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible && $partie == $partieCible2 ) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les trois */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant() && $ciblePartieCourant2->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Marionettiste */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::CONDUCTEUR_DE_BUS){
                                    //TODO Parametres
                                        $statutControle = new Statut(StatusEnum::ECHANGE, $ciblePartieCourant, $userPartieCourant);
                                        $statutCibleControle = new Statut(StatusEnum::ECHANGE, $ciblePartieCourant2, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statutControle);
                                        $em->persist($statutCibleControle);
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
     * Fonction du détective
     * @param $idCible int L'id de la cible du détective
     */
    public function detectiveAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::DETECTIVE) {
                                    $statut = new Statut(StatusEnum::DETECTE, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction du docteur
     * @param $idCible int L'id de la cible du docteur
     */
    public function docteurAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::DOCTEUR) {
                                    $statut = new Statut(StatusEnum::SAUVE, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction du escort
     * @param $idCible int L'id de la cible de l'escort
     */
    public function escortAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::ESCORT) {
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
     * Fonction du garde du corps
     * @param $idCible int L'id de la cible du garde du corps
     */
    public function gardeDuCorpsAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::GARDE_DU_CORPS) {
                                    $statut = new Statut(StatusEnum::PROTEGE, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction de l'inspecteur
     * @param $idCible int L'id de la cible de l'inspecteur
     */
    public function inspecteurAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::INSPECTEUR) {
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

    /**
     * Fonction du justicier
     * @param $idCible int L'id de la cible du justicier
     */
    public function justicierAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::JUSTICIER) {
                                    if($userPartieCourant->getCapaciteRestante() > 0) {
                                        $statut = new Statut(StatusEnum::TUE, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction du médecin legiste
     * @param $idCible int L'id de la cible du médecin légiste
     */
    public function medecinLegisteAction($idCible){

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
                        if ($userPartieCourant->getVivant() && !$ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Medecin legiste  */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::MEDECIN_LEGISTE) {
                                    $statut = new Statut(StatusEnum::INSPECTE_MORT, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction du sheriff
     * @param $idCible int L'id de la cible du sheriff
     */
    public function sheriffAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::SHERIFF) {
                                    $statut = new Statut(StatusEnum::VERIFIE, $ciblePartieCourant, $userPartieCourant);
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
     * Fonction du veilleur
     * @param $idCible int L'id de la cible du veilleur
     */
    public function veilleurAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::VEILLEUR) {
                                    $statut = new Statut(StatusEnum::VERIFIE, $ciblePartieCourant, $userPartieCourant);
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