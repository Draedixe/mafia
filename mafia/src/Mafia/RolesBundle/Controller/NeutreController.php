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
            ->getRepository('MafiaRolesBundle:User');

        $cible = $repositoryUser->find($idCible);

        $userGlobal = $this->getUser();
        $userPartieCourant = $userGlobal->getUserCourant();

        $ciblePartieCourant = $cible->getUserCourant();

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
} 