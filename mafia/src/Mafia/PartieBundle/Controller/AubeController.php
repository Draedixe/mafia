<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\StatusEnum;
use Mafia\PartieBundle\Entity\Statut;
use Mafia\RolesBundle\Entity\OptionsRolesEnum;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\PartieBundle\Entity\UserPartie;
use Symfony\Component\HttpFoundation\JsonResponse;

class AubeController extends FunctionsController{

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
        $res = null;
        foreach($arrayOfStatuts as $cle => $statut)
        {
            if($statut->getActeur() == $acteur){
                $res = $statut;
            }
        }
        return $res;
    }

    function rechercheOptionParEnum($enum, $arrayOfOptions){

        foreach($arrayOfOptions as $cle => $option)
        {
            if($option->getEnumOption() == $enum){
                return $option;
            }
        }
        return null;
    }

    function rechercheOptionsParRole($role, $arrayOfOptions){
        $res = array();
        foreach($arrayOfOptions as $cle => $option)
        {
            if($option->getRole() == $role){
                $res[] = $option;
            }
        }
        return $res;
    }

    function rechercheUsersParRole($role, $arrayOfUsers){
        $res = array();
        foreach($arrayOfUsers as $cle => $user)
        {
            if($user->getRole() == $role){
                $res[] = $user;
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
                    if(!$partie->isTraitementAubeEnCours())
                    {
                        $em = $this->getDoctrine()->getManager();
                        /* On prend le jeton du webaphore */

                        $partie->setTraitementAubeEnCours(true);
                        $em->persist($partie);
                        $em->flush();
                        $chat = $partie->getChat();

                        /** Lancons les traitements! **/

                        /* Prenons des repositories */

                        $repositoryUser = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('MafiaPartieBundle:UserPartie');

                        $repositoryStatut = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('MafiaPartieBundle:Statut');



                        $usersPartie = $repositoryUser->findBy(array('partie'=>$partie));
                        $composition = $partie->getComposition();
                        $rolesComposEnJeu = $composition->getRolesCompo();
                        $rolesEnJeu = array();
                        foreach($rolesComposEnJeu as $roleCompo){
                            $enumRole = $roleCompo->getRole()->getEnumRole();
                            $rolesEnJeu[$enumRole] = $roleCompo->getRole();
                        }
                        $optionsRoles = $composition->getOptionsRoles();

                        $statutsPartie = array();
                        foreach($usersPartie as $userPartie){
                            $query = $em->createQuery('SELECT s FROM Mafia\PartieBundle\Entity\Statut s WHERE s.acteur = :idUser');
                            $query->setParameters(array(
                                'idUser' => $userPartie->getId()
                            ));
                            $statutsPartie = array_merge($statutsPartie,$query->getResult());
                        }

                        /* Voila, on a pris des repositories et les users et les statuts */

                        /* Traitons les Statuts pour les mettre comme nécessaire */

                        /* Prétraitement: le kill de la mafia */

                        if(isset($rolesEnJeu[RolesEnum::MAFIOSO]) || isset($rolesEnJeu[RolesEnum::PARRAIN])){
                            $parrainEnJeu = array();
                            $mafiososEnJeu = array();
                            if(isset($rolesEnJeu[RolesEnum::PARRAIN])){
                                $parrainEnJeu = $this->rechercheUsersParRole($rolesEnJeu[RolesEnum::PARRAIN],$usersPartie);
                            }
                            if(isset($rolesEnJeu[RolesEnum::MAFIOSO])) {
                                $mafiososEnJeu = $this->rechercheUsersParRole($rolesEnJeu[RolesEnum::MAFIOSO], $usersPartie);
                            }

                            $votesMafia = array();
                            foreach($usersPartie as $us){
                                $votesMafia[$us->getId()] = 0;
                            }
                            //Vote du parrain
                            foreach($parrainEnJeu as $p){
                                $voteParrain = $this->rechercheStatutParActeur($p, $statutsPartie);
                                if($voteParrain != null){
                                    $em->remove($voteParrain);
                                    $em->remove($voteParrain);
                                    $votesMafia[$voteParrain->getVictime()->getId()] += 20;
                                    $em->flush();
                                }
                            }
                            //Vote des mafiosos
                            foreach($mafiososEnJeu as $m){
                                $voteMafioso = $this->rechercheStatutParActeur($m, $statutsPartie);
                                if($voteMafioso != null){
                                    $em->remove($voteMafioso);
                                    $votesMafia[$voteMafioso->getVictime()->getId()] += 1;
                                    $em->flush();
                                }
                            }

                            $voteMax = -1;
                            $voteCible = -1;
                            foreach($votesMafia as $key=>$v){
                                if($v > $voteMax && $v != 0){
                                    $voteCible = $key;
                                    $voteMax = $v;
                                }
                            }
                            if($voteCible != -1){
                                if(count($mafiososEnJeu) > 0){
                                    $rand = rand(0, count($mafiososEnJeu)-1);
                                    $userCible = $repositoryUser->findOneBy(array("id"=>$voteCible));
                                    $killMafia = new Statut(StatusEnum::TUE, $userCible, $mafiososEnJeu[$rand]);
                                    $em->persist($killMafia);
                                    $em->flush();
                                }
                                else{
                                    $userCible = $repositoryUser->findOneBy(array("id"=>$voteCible));
                                    $killMafia = new Statut(StatusEnum::TUE, $userCible, $parrainEnJeu[0]);
                                    $em->persist($killMafia);
                                    $em->flush();
                                }
                            }
                        }

                        $statutsPartie = array();
                        foreach($usersPartie as $userPartie)
                        {
                            $query = $em->createQuery('SELECT s FROM Mafia\PartieBundle\Entity\Statut s WHERE s.acteur = :idUser');
                            $query->setParameters(array(
                                'idUser' => $userPartie->getId()
                            ));
                            $statutsPartie = array_merge($statutsPartie,$query->getResult());
                        }
                        $saveStatuts = $statutsPartie;


                        // MARIONETTE SUR BLOQUEUR
                        // CONDUCTEUR
                        // BLOQUEURS
                        //$statutsATraiterMarionettes = $this->rechercheStatutParEnum(StatusEnum::CONTROLE,$statutsPartie);
                        // MARIONETTES
                        /*$statutsATraiterMarionettes = $this->rechercheStatutParEnum(StatusEnum::CONTROLE,$statutsPartie);
                        $statutsATraiterVictimes = $this->rechercheStatutParEnum(StatusEnum::CIBLE_CONTROLE,$statutsPartie);
                        foreach($statutsATraiterMarionettes as $statutATraiter){
                            $cibleConcernee = $this->rechercheStatutParActeur($statutATraiter->getActeur(),$statutsATraiterVictimes);
                            $controle = $this->rechercheStatutParActeur($statutATraiter->getVictime(),$statutsATraiterMarionettes);
                            $controle->setVictime($cibleConcernee);
                            $statutsPartie[] = $controle;
                        }*/
                        // CONDUCTEUR DE BUS

                        // Tueur en série
                        if(isset($rolesEnJeu[RolesEnum::TUEUR_EN_SERIE])){
                            $optionInvul = $this->rechercheOptionParEnum(OptionsRolesEnum::TUEUR_SERIE_INVULNERABLE_NUIT,$optionsRoles);
                            $optionAdBlock = $this->rechercheOptionParEnum(OptionsRolesEnum::TUEUR_SERIE_TUE_BLOQUEUR_ROLE,$optionsRoles);
                            //$optionAdDetect = $this->rechercheOptionParEnum(OptionsRolesEnum::TUEUR_SERIE_IMMUNISER_DETECTION,$optionsRoles);
                            $tueursEnSerieEnJeu = $this->rechercheUsersParRole($rolesEnJeu[RolesEnum::TUEUR_EN_SERIE],$usersPartie);

                            foreach($tueursEnSerieEnJeu as $tueur){
                                // Par défaut true, donc null => inchangé => true
                                if($optionInvul == null or $optionInvul->getValeur()){
                                    $statutsPartie[] = new Statut(StatusEnum::GILET,$tueur,$tueur);
                                }
                                // Par défaut false, donc null => inchangé => false
                                if($optionAdBlock != null){
                                    if($optionAdBlock->getValeur()){
                                        $bloqueurs = $this->rechercheStatutParEnumEtVictime(StatusEnum::BLOQUE,$tueur,$statutsPartie);
                                        foreach($bloqueurs as $bloqueur){
                                            $statutsPartie[] = new Statut(StatusEnum::TUE,$bloqueur->getActeur(),$tueur);
                                        }
                                    }
                                }
                                /* BLOQUAGE DETECTION
                                 if($optionAdBlock != null){
                                    if($optionAdBlock->getValeur()){
                                        $bloqueurs = $this->rechercheStatutParEnumEtVictime(StatusEnum::BLOQUE,$tueur,$statutsPartie);
                                    }
                                }*/
                            }

                        }





                        // LES GILETS
                        /*$statutsATraiter = $this->rechercheStatutParEnum(StatusEnum::GILET,$statutsPartie);
                        foreach($statutsATraiter as $statutATraiter){
                            $this->rechercheStatutParEnumEtVictime(StatusEnum::TUE,$statutATraiter->getActeur(),$statutsPartie);
                        }*/

                        /** Fin des traitements **/

                        /* On envoie les infos */

                        $statutsATraiter = $this->rechercheStatutParEnum(StatusEnum::TUE,$statutsPartie);

                        $statutsTues = array_merge($statutsATraiter,$this->rechercheStatutParEnum(StatusEnum::TUE_ANTI_INVUL,$statutsPartie));

                        foreach($statutsTues as $statutTue){
                            $statutTue->getVictime()->setVivant(false);
                            $this->messageSysteme($em,$chat,$statutTue->getVictime()->getNom() . " a été tué par " . $statutTue->getActeur()->getNom());
                        }
                        /* Les infos ont été envoyées */

                        $em = $this->getDoctrine()->getManager();
                        /* On libère le jeton du webaphore */
                        $partie->setTraitementAubeEnCours(false);
                        foreach($usersPartie as $user){
                            $em->persist($user);
                        }

                        foreach($saveStatuts as $statut){
                            if($statut->getEnumStatut() != StatusEnum::ESSENCE){
                                $em->remove($statut);
                            }
                        }
                        $em->persist($partie);
                        $em->flush();
                        return new JsonResponse();
                    }
                }
            }
        }

        return new JsonResponse();
    }


} 