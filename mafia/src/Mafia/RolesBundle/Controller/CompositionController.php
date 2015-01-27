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

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:RolesCompos');
        $roles = $repository->findBy(array('composition' => $compo));

        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('MafiaRolesBundle:Importance');
        $importances = $repository->findBy(array('composition' => $compo));

        $importancesArr = array();
        foreach($importances as $importance)
        {
            $importancesArr[$importance->getRole()->getId()] = $importance->getValeur();
        }

        return $this->render('MafiaRolesBundle:Affichages:vue_composition.html.twig', array(
            'composition' => $compo,
            'options' => $optionsRes,
            'roles' => $roles,
            'importances' => $importancesArr
        ));

    }

    public function ajoutCompositionAction()
    {
        $newComposition = new Composition();
        $request = $this->get('request');
        $composition = $request->get("composition");
        $options = $request->get("options");
        $importances = $request->get("importances");

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        if($options){
            foreach($options as $option)
            {
                $role = $repository->find($option['role']);
                $newOption = new OptionRole();
                $newOption->setRole($role);
                $newOption->setEnumOption($option['option']);
                $newOption->setValeur($option['valeur']);
                $em->persist($newOption);
                $newComposition->addOptionRole($newOption);
            }
        }
        if($request->get("nom") != "")
        {
            $newComposition->setNomCompo($request->get("nom"));
        }
        else
        {
            $newComposition->setNomCompo("Composition");
        }
        $newComposition->setOfficielle(false);
        $arrOccurences = array();
        foreach($composition as $idRole)
        {
            $arrOccurences[$idRole] = $this->nombreOccurences($composition,$idRole);
        }

        $em->persist($newComposition);
        $em->flush();
        foreach($arrOccurences as $idRole => $quantite)
        {
            $roleCompo = new RolesCompos();
            $role = $repository->find($idRole);
            $roleCompo->setRole($role);
            $roleCompo->setComposition($newComposition);
            $roleCompo->setQuantite($quantite);
            $em->persist($roleCompo);
        }
        if($importances){
            foreach($importances as $importance)
            {
                $newImportance = new Importance();
                $role = $repository->find($importance['role']);
                $newImportance->setRole($role);
                $newImportance->setComposition($newComposition);
                $newImportance->setValeur($importance['valeur']);
                $em->persist($newImportance);
            }
        }
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