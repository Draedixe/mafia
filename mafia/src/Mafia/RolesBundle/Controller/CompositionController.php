<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 18/01/2015
 * Time: 19:11
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Composition;
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
        $res = 1;
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
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        foreach($options as $option)
        {
            $role = $repository->find($option->getIdRole());
            $optionsRes[$option->getEnumOption()] = OptionsRoles::getName($role->getEnumRole())[$option->getEnumOption()];
        }

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:RolesCompos');
        $roles = $repository->findBy(array('composition' => $compo));

        return $this->render('MafiaRolesBundle:Affichages:vue_composition.html.twig', array(
            'composition' => $compo,
            'options' => $optionsRes,
            'roles' => $roles
        ));

    }

    public function ajoutCompositionAction()
    {
        $newComposition = new Composition();
        $request = $this->get('request');
        $composition = $request->get("composition");
        $options = $request->get("options");

        $em = $this->getDoctrine()->getManager();
        if($options){
            foreach($options as $option)
            {
                $newOption = new OptionRole();
                $newOption->setIdRole($option['role']);
                $newOption->setEnumOption($option['option']);
                $newOption->setValeur($option['valeur']);
                $em->persist($newOption);
                $newComposition->addOptionRole($newOption);
            }
        }
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
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
        $em->flush();

        return new Response($this->generateUrl('vue_composition', array('id'=>$newComposition->getId())));

    }

    public function recupererNomOptionsAction()
    {
        $request = $this->get('request');
        $role = $request->get("role");

        $resultat = OptionsRoles::getName($role);
        $data = json_encode($resultat);
        return new Response($data);
    }

    public function recupererValeursOptionsAction()
    {
        $request = $this->get('request');
        $role = $request->get("role");
        $option = $request->get("option");

        $resultat = OptionsRoles::getValeursPossibles($role,$option);
        $min = $resultat["min"];
        $max = $resultat["max"];
        $defaut = OptionsRoles::getOptionsDefaut($role,$option);
        return new Response(json_encode(array("min"=>$min,"max"=>$max,"defaut"=>$defaut)));
    }

} 