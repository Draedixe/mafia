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

}