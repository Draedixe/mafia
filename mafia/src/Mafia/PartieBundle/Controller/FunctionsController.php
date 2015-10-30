<?php

namespace Mafia\PartieBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\PartieBundle\Entity\Message;

class FunctionsController extends Controller{
    protected function verifPhase()
    {
        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                $chat = $partie->getChat();
                $parametres = $partie->getParametres();

                $usersPartie = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true));
                if (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp()) > ($partie->getDureePhase() * 60)) {

                    switch ($partie->getPhaseEnCours()) {
                        case PhaseJeuEnum::AUBE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::DISCUSSION);
                            $partie->setDureePhase($parametres->getTempsDeDiscussion());
                            $this->messageSysteme($em,$chat,"C'est l'heure de discuter");
                            break;
                        case PhaseJeuEnum::NUIT :
                            $partie->setPhaseEnCours(PhaseJeuEnum::AUBE);
                            $partie->setDureePhase(0.2);
                            $this->forward('MafiaPartieBundle:Aube:traitementAube');
                            $this->messageSysteme($em,$chat,"C'est l'aube");
                            break;
                        case PhaseJeuEnum::DISCUSSION :
                            $this->razVotes();
                            $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                            $partie->setDureePhase($parametres->getDureeDuJour());
                            $this->messageSysteme($em,$chat,"C'est le jour");
                            break;
                        case PhaseJeuEnum::EXECUTION :
                            $this->razVotes();
                            $accuse = $partie->getAccuse();
                            $accuse->setVivant(false);
                            $this->messageSysteme($em,$chat,$accuse->getNom() . " a été exécuté");
                            $partie->setAccuse(null);
                            $em->persist($accuse);
                            $em->flush();
                            $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                            $partie->setDureePhase($parametres->getDureeDeLaNuit());
                            $this->messageSysteme($em,$chat,"C'est la nuit");
                            break;
                        case PhaseJeuEnum::JOUR :
                            $this->razVotes();

                            $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                            $partie->setDureePhase($parametres->getDureeDeLaNuit());
                            $this->messageSysteme($em,$chat,"C'est la nuit");
                            break;
                        case PhaseJeuEnum::RESULTAT_VOTE :
                            $usersPartieNon = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true, "voteTribunal" => 0));
                            $usersPartieOui = $repositoryUser->findBy(array("partie" => $partie, "vivant" => true, "voteTribunal" => 1));
                            $this->messageSysteme($em,$chat,"Résultat des votes: ".count($usersPartieOui)."-OUI ".count($usersPartieNon)."-NON");
                            if (count($usersPartieOui) > count($usersPartieNon)) {
                                $partie->setPhaseEnCours(PhaseJeuEnum::EXECUTION);
                                $partie->setDureePhase(0.2);
                            } else {
                                $accuse = $partie->getAccuse();
                                $this->messageSysteme($em,$chat,$accuse->getNom() . " a été libéré");
                                $partie->setAccuse(null);
                                if ($partie->getTempsJourRestant() > 0) {
                                    $this->razVotes();
                                    $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
                                    $partie->setDureePhase($partie->getTempsJourRestant());
                                    $this->messageSysteme($em,$chat,"C'est le jour");
                                } else {
                                    $this->razVotes();
                                    $partie->setPhaseEnCours(PhaseJeuEnum::NUIT);
                                    $partie->setDureePhase($parametres->getDureeDeLaNuit());
                                    $this->messageSysteme($em,$chat,"C'est la nuit");
                                }
                            }
                            break;
                        case PhaseJeuEnum::TRIBUNAL_DEFENSE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_VOTE);
                            $partie->setDureePhase($parametres->getTempsTribunal());
                            $this->messageSysteme($em,$chat,"C'est l'heure du vote");
                            break;
                        case PhaseJeuEnum::TRIBUNAL_VOTE :
                            $partie->setPhaseEnCours(PhaseJeuEnum::RESULTAT_VOTE);
                            $partie->setDureePhase(0.25);
                            break;

                    }
                    $partie->setDebutPhase(new \DateTime());

                }
                if ($partie->getPhaseEnCours() == PhaseJeuEnum::JOUR) {
                    $votes = array();

                    foreach ($usersPartie as $joueur) {
                        if ($joueur->getVotePour() != null) {
                            if (!isset($votes[$joueur->getVotePour()->getId()])) {
                                $votes[$joueur->getVotePour()->getId()] = 0;
                            }
                            $votes[$joueur->getVotePour()->getId()]++;
                        }
                    }
                    $majorite = floor(count($usersPartie)/2) + 1;
                    foreach ($votes as $key=>$vote) {
                        if ($vote >= $majorite) {
                            $partie->setPhaseEnCours(PhaseJeuEnum::TRIBUNAL_DEFENSE);
                            $partie->setTempsJourRestant($parametres->getDureeDuJour() - (((new \DateTime())->getTimestamp() - $partie->getDebutPhase()->getTimestamp())));
                            $partie->setDureePhase($parametres->getTempsTribunal());
                            $userAccuse = $repositoryUser->findOneBy(array("id" => $key));
                            $partie->setAccuse($userAccuse);
                            $this->razVotes();
                            $this->messageSysteme($em,$chat,"C'est l'heure de se défendre pour ".$userAccuse->getNom());
                        }
                    }

                }
                $em->persist($partie);
                $em->flush();
                return $partie->getPhaseEnCours();
            }
        }
        return -1;
    }

    protected function messageSysteme($em,$chat,$message){
        $newMessage = new Message();
        $newMessage->setType(0);
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser(null);

        $em->persist($newMessage);
        $em->flush();
    }

    protected function razVotes(){
        $userGlobal = $this->getUser();
        if($userGlobal != null) {
            $user = $userGlobal->getUserCourant();
            if ($user != null) {
                $partie = $user->getPartie();
                $qB = $this->getDoctrine()->getManager()->createQueryBuilder();
                $qB->update('MafiaPartieBundle:UserPartie', 'j')
                    ->set('j.votePour', '?1')
                    ->set('j.voteTribunal', '?2')
                    ->where('j.partie = ?3')
                    ->andWhere('j.vivant = ?4')
                    ->setParameter(1, null)
                    ->setParameter(2, 2)
                    ->setParameter(3, $partie)
                    ->setParameter(4, true);

                $query = $qB->getQuery();

                $query->getResult();
            }
        }
    }

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
                        foreach($usersPartie as $userPartie)
                        {
                            $query = $em->createQuery('SELECT s FROM Mafia\PartieBundle\Entity\Statut s WHERE s.acteur = :idUser OR s.victime = :idUser');
                            $query->setParameters(array(
                                'idUser' => $userPartie->getId()
                            ));
                            $statutsPartie = array_merge($statutsPartie,$query->getResult());
                        }
                        $saveStatuts = $statutsPartie;

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
                                $votesMafia[$us] = 0;
                            }
                            //Vote du parrain
                            foreach($parrainEnJeu as $p){
                                $voteParrain = $this->rechercheStatutParActeur($p, $statutsPartie);
                                foreach($voteParrain as $v){
                                    $em->remove($v);
                                    $votesMafia[$v->getActeur()] += 20;
                                }
                                $em->flush();
                            }
                            //Vote des mafiosos
                            foreach($mafiososEnJeu as $m){
                                $voteMafioso = $this->rechercheStatutParActeur($m, $statutsPartie);
                                foreach($voteMafioso as $v){
                                    $em->remove($v);
                                    $votesMafia[$v->getActeur()] += 1;
                                }
                                $em->flush();
                            }

                            $voteMax = -1;
                            $voteCible = null;
                            foreach($votesMafia as $key=>$v){
                                if($v > $voteMax){
                                    $voteCible = $key;
                                    $voteMax = $v;
                                }
                            }
                            if($voteCible != null){
                                if(count($mafiososEnJeu) > 0){
                                    $rand = rand(1, count($mafiososEnJeu));
                                    $killMafia = new Statut(StatusEnum::TUE, $voteCible, $mafiososEnJeu[$rand]);
                                    $em->persist($killMafia);
                                    $em->flush();
                                }
                                else{
                                    $killMafia = new Statut(StatusEnum::TUE, $voteCible, $parrainEnJeu[0]);
                                    $em->persist($killMafia);
                                    $em->flush();
                                }
                            }
                        }

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
                        $statutsATraiter = $this->rechercheStatutParEnum(StatusEnum::GILET,$statutsPartie);
                        foreach($statutsATraiter as $statutATraiter){
                            $this->rechercheStatutParEnumEtVictime(StatusEnum::TUE,$statutATraiter->getActeur(),$statutsPartie);
                        }

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
                        return "OK";
                    }
                }
            }
        }
        return "ERROR";
    }
}
