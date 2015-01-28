<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 18/01/2015
 * Time: 19:11
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Composition;
use Mafia\RolesBundle\Entity\Importance;
use Mafia\RolesBundle\Entity\OptionRole;
use Mafia\RolesBundle\Entity\OptionsRoles;
use Mafia\RolesBundle\Entity\OptionsRolesEnum;
use Mafia\RolesBundle\Entity\Role;
use Mafia\RolesBundle\Entity\RolesCompos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CompositionController extends Controller{

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

    public function creationCompositionAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');

        $roles = $repository->findAll();

        return $this->render('MafiaRolesBundle:Formulaires:creer_composition.html.twig', array(
            'roles' => $roles
        ));
    }

    public function affichageCompositionAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $compo = $repository->find($id);

        $options = $compo->getOptionsRoles();
        $optionsRes = array();
        foreach($options as $option)
        {
            $role = $option->getRole();
            $optionsRes[$option->getEnumOption()] = OptionsRoles::getName($role->getEnumRole())[$option->getEnumOption()];
        }

        $importancesArr = array();
        foreach($compo->getImportances() as $importance)
        {
            $importancesArr[$importance->getRole()->getId()] = $importance->getValeur();
        }

        return $this->render('MafiaRolesBundle:Affichages:vue_composition.html.twig', array(
            'composition' => $compo,
            'options' => $optionsRes,
            'roles' => $compo->getRolesCompo(),
            'importances' => $importancesArr
        ));

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

        // Les roles \\
        if($composition){
            $arrOccurences = array();
            foreach($composition as $idRole)
            {
                $arrOccurences[$idRole] = $this->nombreOccurences($composition,$idRole);
            }
            foreach($arrOccurences as $idRole => $quantite)
            {
                $role = $repository->find($idRole);
                if($role != null) {
                    $newRoleCompo = $repositoryRoleCompo->findOneBy(array("quantite" => $quantite, "role" => $role));
                    if($newRoleCompo == null){
                        $newRoleCompo = new RolesCompos();
                        $newRoleCompo->setRole($role);
                        $newRoleCompo->setQuantite($quantite);
                        $em->persist($newRoleCompo);
                    }
                    $newComposition->addRoleCompo($newRoleCompo);
                }
                else
                {
                    return new JsonResponse(array('type' => "Role", 'erreur' => "Le role ".$idRole." n'existe pas"));
                }
            }

        }
        else
        {
            return new JsonResponse(array('type' => "Role", 'erreur' => "Merci d'ajouter des rôles à la composition"));
        }

        $em->persist($newComposition);
        $em->flush();
        $em->flush();

        return new Response($this->generateUrl('vue_composition', array('id'=>$newComposition->getId())));

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

    public function affichageListeCompositionsAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $compositions = $repository->findAll();



        return $this->render('MafiaRolesBundle:Affichages:liste_compositions.html.twig', array(
            'compositions' => $compositions
        ));

    }
}