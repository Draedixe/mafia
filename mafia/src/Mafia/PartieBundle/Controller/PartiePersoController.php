<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 17/10/2015
 * Time: 17:18
 */

namespace Mafia\PartieBundle\Controller;
use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Parametres;
use Mafia\PartieBundle\Entity\Partie;
use Mafia\PartieBundle\Entity\PhaseJeuEnum;
use Mafia\RolesBundle\Entity\CategorieCompo;
use Mafia\RolesBundle\Entity\Importance;
use Mafia\RolesBundle\Entity\OptionRole;
use Mafia\RolesBundle\Entity\OptionsRoles;
use Mafia\RolesBundle\Entity\RolesCompos;
use Proxies\__CG__\Mafia\RolesBundle\Entity\Composition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class PartiePersoController extends Controller{

    public function preparationPartieAction(){
        $userGlobal = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $repositoryParam = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Parametres');
        $userDansAutrePartie = $userGlobal->getUserCourant();

        //Si on récupère quelquechose (si on ne trouve rien on crée un userPartie)
        if($userDansAutrePartie != null) {
            //On trouve le user dans une partie deja terminée, on met a jour le user
            if ($userDansAutrePartie->getPartie()->isTerminee() || !$userDansAutrePartie->getVivant()) {
                $this->getUser()->setUserCourant(NULL);
                $userDansAutrePartie = null;
            }
            //Si le user est dans une partie commencee et qu'il est encore vivant, il y retourne
            else if ($userDansAutrePartie->getPartie()->isCommencee() && $userDansAutrePartie->getVivant()) {
                return $this->forward('MafiaPartieBundle:Jeu:debutPartie');
            }
        }

        $partie = new Partie();
        $partie->setNomPartie("Partie de " . $userGlobal->getUsername());
        $partie->setPhaseEnCours(PhaseJeuEnum::JOUR);
        $partie->setDureePhase(1);
        $partie->setTempsJourRestant(1);
        $partie->setDebutPhase(new \DateTime());
        $partie->setCommencee(false);
        $partie->setTerminee(false);
        $partie->setMaireAnnonce(false);
        $partie->setTypePartie("perso");

        $allParam = $repositoryParam->findAll();
        if (count($allParam) == 0) {
            $param = new Parametres();
            $param->setNomParametres("Par Défaut");
            $param->setOfficiel(true);
            $em->persist($param);
            $em->flush();
        } else {
            $param = $allParam[0];
        }

        $partie->setParametres($param);

        $repositoryRoles = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        //Compo spéciale pour les tests
        $compo = $repositoryRoles->findOneBy(array("nomCompo" => "Officielle"));

        $partie->setComposition($compo);

        //Création du chat
        $chat = new Chat();
        $em->persist($chat);
        $em->flush();

        $partie->setChat($chat);

        $em->persist($partie);
        $em->flush();

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');

        $roles = $repository->findAll();

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Categorie');

        $categories = $repository->findAll();
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');

        $user = $userGlobal->getUserCourant();
        $partie = $user->getPartie();
        $chat = $partie->getChat();

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Message');


        $pid = 0;

        $messages = $repositoryMessage->myFind($chat, $pid);

        $data = array();
        $id = 0;
        foreach ($messages as $message) {
            $data[$id] = array("id" => $message->getId(), "pseudo" => $message->getUser()->getUsername(), "message" => $message->getTexte());
            $id++;
        }

        $userList = $repositoryUser->findBy(array("partie" => $partie));
        //LISTE DES UTILISATEURS
        $userData = array();
        $id = 0;
        foreach ($userList as $ul) {
            if ($ul == $partie->getCreateur()) {
                $userData[$id] = $ul->getUser()->getUsername() . " - Créateur";
            } else {
                $userData[$id] = $ul->getUser()->getUsername();
            }
            $id++;
        }
        $formBuilder = $this->get('form.factory')->create();
        $formBuilder
            ->add('message', 'text', array('label' => 'Message'));
        return $this->render('MafiaPartieBundle:Affichages:preparation_perso.html.twig', array(
            'roles' => $roles,
            'categories' => $categories,
            'partie' => $partie,
            'messages' => $data,
            'users' => $userData,
            'form' => $formBuilder->createView()
        ));
    }

    function nombreOccurences($array, $idRole)
    {
        $res = 0;
        foreach($array as $valeur)
        {
            if ($valeur == $idRole) {
                $res++;
            }
        }
        return $res;
    }

    public function ajoutCompositionAction()
    {
        // Recupérations des infos envoyées en POST \\
        $request = $this->get('request');
        $composition = $request->get("composition");
        $options = $request->get("options");
        $importances = $request->get("importances");


        // On prepare \\
        $newComposition = new Composition();
        if($request->get("nom") != "")
        {
            $newComposition->setNomCompo($request->get("nom"));
        }
        else
        {
            $newComposition->setNomCompo("Composition");
        }
        $newComposition->setOfficielle(false);

        // ACCES BDD \\
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $repositoryOptions = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:OptionRole');
        $repositoryImportances = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Importance');
        $repositoryRoleCompo = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:RolesCompos');
        $repositoryCategorieCompo = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:CategorieCompo');
        $repositoryCategorie = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Categorie');
        $em = $this->getDoctrine()->getManager();

        // Les options \\
        if($options){
            foreach($options as $option)
            {
                if($option['option'] >= 0 and $option['option'] <= 124){
                    $role = $repository->find($option['role']);
                    if($role != null) {
                        $newOption = $repositoryOptions->findOneBy(array("enumOption"=>$option['option'],"valeur"=>$option['valeur'],"role"=>$role));
                        if($newOption == null)
                        {
                            $newOption = new OptionRole();
                            $newOption->setRole($role);
                            $newOption->setEnumOption($option['option']);
                            $newOption->setValeur($option['valeur']);
                            $em->persist($newOption);
                        }
                        $newComposition->addOptionRole($newOption);
                    }
                    else
                    {
                        return new JsonResponse(array('type' => "Role", 'erreur' => "Le role ".$option['role']." n'existe pas"));
                    }
                }
            }
        }

        // Les importances \\
        if($importances){
            foreach($importances as $importance)
            {
                if($importance['valeur'] >= 0 and $importance['valeur'] <= 200){

                    $role = $repository->find($importance['role']);
                    if($role != null) {
                        $newImportance = $repositoryImportances->findOneBy(array("valeur" => $importance['valeur'], "role" => $role));
                        if ($newImportance == null) {
                            $newImportance = new Importance();
                            $newImportance->setRole($role);
                            $newImportance->setValeur($importance['valeur']);
                            $em->persist($newImportance);
                        }
                        $newComposition->addImportance($newImportance);
                    }
                    else
                    {
                        return new JsonResponse(array('type' => "Role", 'erreur' => "Le role ".$importance['role']." n'existe pas"));
                    }
                }
                else
                {
                    return new JsonResponse(array('type' => "Importance", 'erreur' => "L'importance du rôle ".$importance['role']." doit être comprise en 0 et 200, actuellement elle est à ".$importance['valeur']));
                }
            }
        }
        else
        {
            return new JsonResponse(array('type' => "Importance", 'erreur' => "Merci de préciser des importances pour les rôle de la composition"));
        }

        // Les roles et categories \\
        if($composition){
            $arrOccurences = array();
            foreach($composition as $idRole)
            {
                $arrOccurences[$idRole] = $this->nombreOccurences($composition,$idRole);
            }
            foreach($arrOccurences as $idRole => $quantite)
            {
                if($idRole >= 0){
                    $role = $repository->find($idRole);
                    if($role != null) {
                        $newRoleCompo = $repositoryRoleCompo->findOneBy(array("quantite" => $quantite, "role" => $role));
                        if($newRoleCompo == null){
                            $newRoleCompo = new RolesCompos($role,$quantite);
                            $em->persist($newRoleCompo);
                        }
                        $newComposition->addRoleCompo($newRoleCompo);
                    }
                    else
                    {
                        return new JsonResponse(array('type' => "Role", 'erreur' => "Le role ".$idRole." n'existe pas"));
                    }
                }
                else
                {
                    $categorie = $repositoryCategorie->find(-1 * $idRole);
                    if($categorie != null) {
                        $newCategorieCompo = $repositoryCategorieCompo->findOneBy(array("quantite" => $quantite, "categorie" => $categorie));
                        if($newCategorieCompo == null){
                            $newCategorieCompo = new CategorieCompo($categorie,$quantite);
                            $em->persist($newCategorieCompo);
                        }
                        $newComposition->addCategorieCompo($newCategorieCompo);
                    }
                    else
                    {
                        return new JsonResponse(array('type' => "Categorie", 'erreur' => "La catégorie ".$idRole." n'existe pas"));
                    }
                }
            }

        }
        else
        {
            return new JsonResponse(array('type' => "Role", 'erreur' => "Merci d'ajouter des rôles à la composition"));
        }
        $em->persist($newComposition);
        $em->flush();
        $partie = $this->getUser()->getUserCourant()->getPartie();
        $partie->setComposition($newComposition);
        $em->persist($partie);
        $em->flush();
        return new JsonResponse(array('SUCCESS'));
    }

    public function recupererNomOptionsAction()
    {
        $request = $this->get('request');
        $role = $request->get("role");

        $resultat = OptionsRoles::getName($role);
        if(count($resultat) > 0)
        {
            $arrFinal = array();
            foreach($resultat as $enumOption => $nom)
            {
                $arrValeur = OptionsRoles::getValeursPossibles($role,$enumOption);
                $arrFinal[$enumOption] = array('nom' => $nom, 'min'=> $arrValeur["min"], 'max' => $arrValeur["max"], 'defaut' => OptionsRoles::getOptionsDefaut($role,$enumOption));
            }
            $data = json_encode($arrFinal);
            return new Response($data);

        }
        else return new Response(null);
    }
} 