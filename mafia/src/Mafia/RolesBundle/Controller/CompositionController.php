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
        $composition = new Composition();
        $composition->setNomCompo("Composition de " . $this->getUser()->getUsername());
        if ($this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')) {
            $composition->setOfficielle(true);
        }
        else{
            $composition->setOfficielle(false);
        }
        $composition->setCreateur($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($composition);
        $em->flush();

        return $this->redirect($this->generateUrl('modifier_composition', array('id'=>$composition->getId())));
    }
    public function modificationCompositionAction($id)
    {
        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('MafiaRolesBundle:Composition');

        $composition = $repository->find($id);

        if($composition != null){
            if($composition->getCreateur() == $this->getUser() or $this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')){
                $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MafiaRolesBundle:Role');

                $roles = $repository->findAll();

                $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MafiaRolesBundle:Categorie');

                $categories = $repository->findAll();

                return $this->render('MafiaRolesBundle:Formulaires:creer_composition.html.twig', array(
                    'roles' => $roles,
                    'categories' => $categories,
                    'composition' => $composition
                ));
            }
        }
        return $this->redirect($this->generateUrl('creation_composition', array()));

    }

    public function changerNomAction(){
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $request = $this->get('request');
        $compo = $repository->find($request->get("id"));
        if($compo != null){
            if($compo->getCreateur() == $this->getUser()){
                $compo->setNomCompo($request->get("nom"));
                $em = $this->getDoctrine()->getManager();
                $em->persist($compo);
                $em->flush();
                return new JsonResponse(array('statut' => "SUCCESS"));
            }else{
                return new JsonResponse(array('statut' => "FAIL",'erreur' => "Vous n'etes pas le createur de cette composition"));
            }
        }else{
            return new JsonResponse(array('statut' => "FAIL",'erreur' => "Cette composition n'existe pas"));
        }

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
            'importances' => $importancesArr
        ));

    }

    public function ajoutRoleCompoAction(){
        $request = $this->get('request');
        $idRole = $request->get("idRole");
        $idCompo = $request->get("idCompo");

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $composition = $repository->find($idCompo);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $role = $repository->find($idRole);

        $em = $this->getDoctrine()->getManager();
        if($composition != null){
            if($composition->getCreateur() == $this->getUser() or $this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')){
                if($role != null){
                    if($role->isRoleUnique()){
                        if(!$composition->isDansCompo($role)) {
                            $composition->addRoleCompo(new RolesCompos($role,1));
                            $em->persist($composition);
                            $em->flush();
                            return new JsonResponse(array('statut' => "SUCCESS"));
                        }else{
                            return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_role_unique));
                        }
                    }else{
                        $roleCompo = $composition->getRoleCompo($role);
                        if($roleCompo == null){
                            $composition->addRoleCompo(new RolesCompos($role,1));
                        }else{
                            $qte = $roleCompo->getQuantite();
                            $composition->removeRoleCompo($roleCompo);
                            $composition->addRoleCompo($role,$qte+1);
                        }
                        $em->persist($composition);
                        $em->flush();
                        return new JsonResponse(array('statut' => "SUCCESS"));
                    }
                }else{
                    return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_role_non_existant));
                }
            }else{
                return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_createur));
            }
        }else{
            return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_existant));
        }
    }

    public function changerOptionRoleCompoAction(){
        $request = $this->get('request');
        $idRole = $request->get("idRole");
        $idCompo = $request->get("idCompo");
        $valeur = $request->get("valeur");
        $enumOption = $request->get("enumOption");

        $repositoryOptions = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:OptionRole');

        $repositoryRole = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');

        $role = $repositoryRole->find($idRole);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');

        $composition = $repository->find($idCompo);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $role = $repository->find($idRole);

        $em = $this->getDoctrine()->getManager();

        if($composition != null){
            if($composition->getCreateur() == $this->getUser() or $this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')){
                if($role != null){
                    $arrValeur = OptionsRoles::getValeursPossibles($role->getEnumRole(),$enumOption);
                    if($valeur >= $arrValeur["min"] && $valeur <= $arrValeur["max"]){
                        $newOption = $repositoryOptions->findOneBy(array("enumOption"=>$enumOption,"valeur"=>$valeur,"role"=>$role));
                        if($newOption == null)
                        {
                            $newOption = new OptionRole();
                            $newOption->setRole($role);
                            $newOption->setEnumOption($enumOption);
                            $newOption->setValeur($valeur);
                            $em->persist($newOption);
                        }
                        $composition->addOptionRole($newOption);
                        $em->persist($composition);
                        $em->flush();
                        return new JsonResponse(array('statut' => "SUCCESS"));
                    }else{
                        return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_option_valeur_invalide));
                    }
                }else{
                    return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_role_non_existant));
                }
            }else{
                return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_createur));
            }
        }else{
            return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_existant));
        }
    }

    public function recupererNomOptionsAction()
    {
        $request = $this->get('request');
        $role = $request->get("role");
        $idCompo = $request->get("compo");

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $composition = $repository->find($idCompo);

        $resultat = OptionsRoles::getName($role);
        if(count($resultat) > 0)
        {
            $arrFinal = array();
            foreach($resultat as $enumOption => $nom)
            {
                $arrValeur = OptionsRoles::getValeursPossibles($role,$enumOption);
                $optionDansCompo = $composition->getOptionRole($enumOption);
                if($optionDansCompo != null) {
                    $valeur = $optionDansCompo->getValeur();
                }else{
                    $valeur = OptionsRoles::getOptionsDefaut($role,$enumOption);
                }
                $arrFinal[$enumOption] = array('nom' => $nom, 'min'=> $arrValeur["min"], 'max' => $arrValeur["max"], 'valeur' => $valeur);
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

    public function recupererValeurImportanceAction(){
        $request = $this->get('request');
        $idRole = $request->get("idRole");
        $idCompo = $request->get("idCompo");

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $composition = $repository->find($idCompo);
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $role = $repository->find($idRole);

        if($composition != null){
            if($composition->getCreateur() == $this->getUser() or $this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')){
                if($role != null){
                    $importance = $composition->getImportanceDuRole($role);
                    if($importance == null){
                        $valeur = 100;
                    }else{
                        $valeur = $importance->getValeur();
                    }
                    return new JsonResponse(array('statut' => "SUCCESS",'valeur' => $valeur));
                }else{
                    return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_role_non_existant));
                }
            }else{
                return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_createur));
            }
        }else{
            return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_existant));
        }
    }
    public function changerValeurImportanceAction(){
        $request = $this->get('request');
        $idRole = $request->get("idRole");
        $idCompo = $request->get("idCompo");
        $valeur = $request->get("valeur");

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Composition');
        $composition = $repository->find($idCompo);
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $role = $repository->find($idRole);
        $em = $this->getDoctrine()->getManager();
        if($composition != null){
            if($composition->getCreateur() == $this->getUser() or $this->get('security.context')->isGranted('ROLE_SUPER_MODERATEUR')){
                if($role != null){
                    $importance = $composition->getImportanceDuRole($role);
                    if($importance != null){
                        $composition->removeImportance($importance);
                    }
                    $repository = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MafiaRolesBundle:Importance');
                    $importance = $repository->findBy(array("role" => $role,"valeur" => $valeur));
                    if($importance == null){
                        $importance = new Importance($role,$valeur);
                        $em->persist($importance);
                    }

                    $composition->addImportance($importance);
                    $em->persist($composition);
                    $em->flush();
                    return new JsonResponse(array('statut' => "SUCCESS",'valeur' => $valeur));
                }else{
                    return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_role_non_existant));
                }
            }else{
                return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_createur));
            }
        }else{
            return new JsonResponse(array('statut' => "FAIL",'erreur' => \MessageEnum::ERREUR_composition_non_existant));
        }
    }
}