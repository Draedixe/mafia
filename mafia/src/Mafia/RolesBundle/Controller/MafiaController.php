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
use Mafia\RolesBundle\Entity\FactionEnum;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MafiaController extends Controller {
    /**
     * Fonction appelée lorsque la mafia veut tuer quelqu'un
     * @param $idCible int L'id de la cible des mafioso
     */
    public function mafiosoAction($idCible){

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
                                if ($userPartieCourant->getRole()->getEnumFaction() == FactionEnum::MAFIA) {
                                    if($ciblePartieCourant->getRole()->getEnumFaction() != FactionEnum::MAFIA){
                                        $statutTue = new Statut(StatusEnum::TUE, $ciblePartieCourant, $userPartieCourant);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->persist($statutTue);
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