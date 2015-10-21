<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/10/2015
 * Time: 23:23
 */

namespace Mafia\RolesBundle\Controller;
use Mafia\PartieBundle\Entity\StatusEnum;
use Mafia\PartieBundle\Entity\Statut;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NeutreController extends Controller {


    public function tueurEnSerieAction($idCible){

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
                if($partie == $partieCible){

                    /* S'ils sont bien vivants tous les deux */
                    if($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()){

                        /* Si le joueur a bien un rôle */
                        if($userPartieCourant->getRole() != null){

                            /* Si le joueur est bien Tueur en série */
                            if($userPartieCourant->getRole()->getEnumRole() == RolesEnum::TUEUR_EN_SERIE){
                                $statutMort = new Statut(StatusEnum::TUE,$ciblePartieCourant,$userPartieCourant);
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

    public function pyromaneArroseAction($idCible){

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
                if($partie == $partieCible){

                    /* S'ils sont bien vivants tous les deux */
                    if($userPartieCourant->getVivant() && $ciblePartieCourant->getVivant()){

                        /* Si le joueur a bien un rôle */
                        if($userPartieCourant->getRole() != null){

                            /* Si le joueur est bien Pyromane */
                            if($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PYROMANE){
                                $statutEssence = new Statut(StatusEnum::ESSENCE,$ciblePartieCourant,$userPartieCourant);
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

    public function pyromaneAllumeAction(){

        /* Récupérons un max de repositories ! */
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');


        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        /* Si les 2 joueurs sont en jeu */
        if($userPartieCourant != null){
            $partie = $userPartieCourant->getPartie();

            /* On vérifie que les parties existent bien */
            if($partie != null){

                    /* S'ils sont bien vivants tous les deux */
                    if($userPartieCourant->getVivant()){

                        /* Si le joueur a bien un rôle */
                        if($userPartieCourant->getRole() != null){

                            /* Si le joueur est bien Pyromane */
                            if($userPartieCourant->getRole()->getEnumRole() == RolesEnum::PYROMANE){
                                $em = $this->getDoctrine()->getManager();
                                $query = $em->createQuery('SELECT u FROM Mafia\PartieBundle\Entity\UserPartie u,Mafia\PartieBundle\Entity\Statut s WHERE u.partie. = :idPartie AND u.vivant = true AND s.victime = u.id AND s.acteur = :idActeur AND s.enumStatut = :enumStatut');
                                $query->setParameters(array(
                                    'idPartie' => $partie->getId(),
                                    'idActeur' => $userPartieCourant,
                                    'enumStatut' => StatusEnum::ESSENCE
                                ));
                                $cibles = $query->getResult();
                                foreach ($cibles as $ciblePartieCourant){
                                    $em->persist(new Statut(StatusEnum::TUE,$ciblePartieCourant,$userPartieCourant));
                                }

                                $em->flush();
                            }
                        }
                    }
                }
            }
        }

} 