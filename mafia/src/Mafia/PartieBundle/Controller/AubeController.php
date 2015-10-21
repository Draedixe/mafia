<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\StatusEnum;
use Mafia\PartieBundle\Entity\Statut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\PartieBundle\Entity\UserPartie;

class AubeController extends Controller{

    function rechercheStatutParEnum($enumStatut, &$arrayOfStatuts){
        $res = array();
        foreach($arrayOfStatuts as $cle => $statut)
        {
            if($statut->getEnumStatut() == $enumStatut){
                $res[] = $statut;
                unset($arrayOfStatuts[$cle]);
            }
        }
        return $res;
    }
    function rechercheStatutParEnumEtVictime($enumStatut,$victime, &$arrayOfStatuts){
        $res = array();
        foreach($arrayOfStatuts as $cle => $statut)
        {
            if($statut->getEnumStatut() == $enumStatut && $statut->getVictime() == $victime){
                $res[] = $statut;
                unset($arrayOfStatuts[$cle]);
            }
        }
        return $res;
    }
    function rechercheStatutParVictime($victime, $arrayOfStatuts){
        $res = array();
        foreach($arrayOfStatuts as $cle => $statut)
        {
            if($statut->getVictime() == $victime){
                $res[] = $statut;
            }
        }
        return $res;
    }
    function rechercheStatutParActeur($acteur, $arrayOfStatuts){

        foreach($arrayOfStatuts as $cle => $statut)
        {
            if($statut->getActeur() == $acteur){
                $res = $statut;
            }
        }
        return $res;
    }


    function traitementAubeAction()
    {
        $userGlobal = $this->getUser();
        if($userGlobal != null){
            $userPartieCourant = $userGlobal->getUserCourant();
            if($userPartieCourant != null){
                $partie = $userPartieCourant->getPartie();
                if($partie != null)
                {
                    //Pour eviter d'avoir plusieurs fois les meme traitements
                    if(!$partie->getTraitementAubeEnCours())
                    {
                        $em = $this->getDoctrine()->getManager();
                        /* On prend le jeton du webaphore */
                        $partie->setTraitementAubeEnCours(true);
                        $em->persist($partie);
                        $em->flush();


                        /** Lancons les traitements! **/

                        /* Prenons des repositories */

                        $repositoryUser = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('MafiaPartieBundle:UserPartie');

                        $repositoryStatut = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('MafiaPartieBundle:Statut');

                        $usersPartie = $repositoryUser->findBy(array('partie'=>$partie));
                        $statutsPartie = array();
                        foreach($usersPartie as $userPartie)
                        {
                            $query = $em->createQuery('SELECT s FROM Mafia\PartieBundle\Entity\Statut s WHERE s.acteur = :idUser OR s.victime = :idUser');
                            $query->setParameters(array(
                                'idUser' => $userPartie->getId()
                            ));
                            array_merge($statutsPartie,$query->getResult());
                        }


                        /* Voila, on a pris des repositories et les users et les statuts */

                        /* Traitons les Statuts pour les mettre comme nécessaire */

                        // LES GILETS
                        $statutsATraiter = $this->rechercheStatutParEnum(StatusEnum::GILET,$statutsPartie);
                        foreach($statutsATraiter as $statutATraiter){
                            $this->rechercheStatutParEnumEtVictime(StatusEnum::TUE,$statutATraiter->getActeur(),$statutsATraiter);
                        }
                        // MARIONETTES
                        $statutsATraiterMarionettes = $this->rechercheStatutParEnum(StatusEnum::CONTROLE,$statutsPartie);
                        $statutsATraiterVictimes = $this->rechercheStatutParEnum(StatusEnum::CIBLE_CONTROLE,$statutsPartie);
                        foreach($statutsATraiterMarionettes as $statutATraiter){
                            $cibleConcernee = $this->rechercheStatutParActeur($statutATraiter->getActeur(),$statutsATraiterVictimes);
                            $controle = $this->rechercheStatutParActeur($statutATraiter->getVictime(),$statutsATraiterMarionettes);
                            $controle->setVictime($cibleConcernee);
                            $statutsPartie[] = $controle;
                        }
                        // CONDUCTEUR DE BUS
                        //TODO LA SUITE


                        /** Fin des traitements **/


                        $em = $this->getDoctrine()->getManager();
                        /* On libère le jeton du webaphore */
                        $partie->setTraitementAubeEnCours(false);
                        $em->persist($partie);
                        $em->flush();
                    }
                }
            }
        }
    }
} 