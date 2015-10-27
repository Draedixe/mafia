<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/10/2015
 * Time: 23:23
 */

namespace Mafia\RolesBundle\Controller;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\StatusEnum;
use Mafia\PartieBundle\Entity\Statut;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NeutreController extends Controller {

    /**
     * Fonction appelée lorsque le TeS tente de tuer quelqu'un
     * @param $idCible int L'id de la cible du TeS
     */
    public function tueurEnSerieAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $repositoryStatut = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Statut');

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
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::TUEUR_EN_SERIE) {
                                    $em = $this->getDoctrine()->getManager();

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
        return new JsonResponse(array("ACTION" => "ERREUR"));
    }

    /**
     * Fonction appelée lorsque le Pyromane tente d'arroser quelqu'un
     * @param $idCible int L'id de la cible du Pyromane
     */
    public function pyromaneArroseAction($idCible){

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
                if($partie == $partieCible){

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {
                        /* S'ils sont bien vivants tous les deux */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Pyromane */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PYROMANE) {
                                    $statutEssence = new Statut(StatusEnum::ESSENCE, $ciblePartieCourant, $userPartieCourant);
                                    $ciblePartieCourant->setEssencePar($userPartieCourant->getId());
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutEssence);
                                    $em->persist($ciblePartieCourant);
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
     * Fonction appelée lorsque le Pyromane veut allumer le feu
     */
    public function pyromaneAllumeAction(){

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

                            /* Si le joueur est bien Pyromane */
                            if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PYROMANE) {
                                $em = $this->getDoctrine()->getManager();
                                $query = $em->createQuery('SELECT u FROM Mafia\PartieBundle\Entity\UserPartie u,Mafia\PartieBundle\Entity\Statut s WHERE u.partie. = :idPartie AND u.vivant = true AND s.victime = u.id AND s.acteur = :idActeur AND s.enumStatut = :enumStatut');
                                $query->setParameters(array(
                                    'idPartie' => $partie->getId(),
                                    'idActeur' => $userPartieCourant,
                                    'enumStatut' => StatusEnum::ESSENCE
                                ));
                                $cibles = $query->getResult();
                                foreach ($cibles as $ciblePartieCourant) {
                                    $em->persist(new Statut(StatusEnum::TUE, $ciblePartieCourant, $userPartieCourant));
                                }

                                $em->flush();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Fonction appelée quand l'Auditeur veut changer le rôle de quelqu'un
     * @param $idCible int L'id de la cible de l'Auditeur
     */
    public function auditeurAction($idCible){
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

                                /* Si le joueur est bien Auditeur */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::AUDITEUR){

                                    $statutChange = new Statut(StatusEnum::ROLE_CHANGE, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutChange);
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
     * Fonction du marionettiste
     * @param $idCible int Id de la victime
     * @param $idCibleDeCible int Id de la victime de la victime
     */
    public function marionettisteAction($idCible,$idCibleDeCible){
        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);
        $cibleDeCiblePartieCourant = $repositoryUser->find($idCibleDeCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 3 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $cibleDeCiblePartieCourant != null  && $userPartieCourant != $ciblePartieCourant){
            $partie = $userPartieCourant->getPartie();
            $partieCible = $ciblePartieCourant->getPartie();
            $partieCibleDeCible = $cibleDeCiblePartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null && $partieCible != null && $partieCibleDeCible != null){

                /* Si ils sont dans la même partie */
                if($partie == $partieCible && $partie == $partieCibleDeCible ) {

                    /* Si c'est bien la nuit */
                    if ($partie->getPhaseEnCours() == PhaseJeuEnum::NUIT) {

                        /* S'ils sont bien vivants tous les trois */
                        if ($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant() && $cibleDeCiblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Marionettiste */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::MARIONETTISTE){

                                    $statutControle = new Statut(StatusEnum::CONTROLE, $ciblePartieCourant, $userPartieCourant);
                                    $statutCibleControle = new Statut(StatusEnum::CIBLE_CONTROLE, $cibleDeCiblePartieCourant, $userPartieCourant);
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
     * Fonction appelée lorsque le Survivant veut s'habiller
     */
    public function survivantAction(){

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
                            if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::SURVIVANT) {

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
     * Fonction appelée lorsque le TdM tente de tuer quelqu'un
     * @param $idCible int L'id de la cible du TdM
     */
    public function tueurEnMasseAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

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

                                /* Si le joueur est bien Tueur de Masse */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::TUEUR_DE_MASSE) {
                                    $statutMort = new Statut(StatusEnum::CIBLE_MASSE, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutMort);
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
     * Fonction appelée lorsque le Amnésique tente de prendre un rôle
     * @param $idCible int L'id de la cible de l'Amnésique
     */
    public function amnesiqueAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

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

                        /* Si l'Amnésique est vivant et vise bien un mort */
                        if ($userPartieCourant->getVivant() && !$ciblePartieCourant->getVivant()) {

                            /* Si le joueur a bien un rôle */
                            if ($userPartieCourant->getRole() != null) {

                                /* Si le joueur est bien Amnésique */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::AMNESIQUE) {
                                    $statutVol = new Statut(StatusEnum::VOL_ROLE, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutVol);
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
     * Fonction appelée lorsque le Bouffon tente de tuer quelqu'un
     * @param $idCible int L'id de la cible du Bouffon
     */
    public function bouffonAction($idCible){

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

                                /* Si le joueur est bien Bouffon */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::BOUFFON) {
                                    $statutRelou = new Statut(StatusEnum::FAIRE_CHIER, $ciblePartieCourant, $userPartieCourant);
                                    $em = $this->getDoctrine()->getManager();
                                    $em->persist($statutRelou);
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
     * Fonction appelée lorsque le Cultiste veut convertir quelqu'un
     * @param $idCible int L'id de la cible du Cultiste
     */
    public function cultisteAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

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

                                /* Si le joueur est bien Cultiste */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::CULTISTE) {
                                    $repositoryRole = $this->getDoctrine()
                                        ->getManager()
                                        ->getRepository('MafiaRoleBundle:Role');
                                    $roleCultiste = $repositoryRole->findOneBy(array('enumRole' => RolesEnum::CULTISTE));
                                    $roleGourou = $repositoryRole->findOneBy(array('enumRole' => RolesEnum::GOUROU));
                                    if($ciblePartieCourant->getRole() != $roleCultiste && $ciblePartieCourant->getRole() != $roleGourou ){
                                        $statutConvertir = new Statut(StatusEnum::CONVERTIR, $ciblePartieCourant, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statutConvertir);
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
     * Fonction appelée lorsque le Gourou veut convertir quelqu'un
     * @param $idCible int L'id de la cible du Gourou
     */
    public function gourouAction($idCible){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $ciblePartieCourant = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null && $ciblePartieCourant != null && $ciblePartieCourant != $userPartieCourant){
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

                                /* Si le joueur est bien Gouou */
                                if ($userPartieCourant->getRole()->getEnumRole() == RolesEnum::GOUROU) {
                                    if($userPartieCourant->getCapaciteRestante() > 0) {
                                        $statutConvertir = new Statut(StatusEnum::SAUVE_CONVERTIR, $ciblePartieCourant, $userPartieCourant);
                                        $userPartieCourant->setCapaciteRestante($userPartieCourant->getCapaciteRestante()-1);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statutConvertir);
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