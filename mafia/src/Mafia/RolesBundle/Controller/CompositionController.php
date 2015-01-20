<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 18/01/2015
 * Time: 19:11
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Composition;
use Mafia\RolesBundle\Entity\OptionsRoles;
use Mafia\RolesBundle\Entity\OptionsRolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CompositionController extends Controller{


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

    public function ajoutCompositionAction()
    {
        $newComposition = new Composition();
        $request = $this->get('request');
        $composition = $request->get("composition");

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');
        $newComposition->setNomCompo("Test");
        $newComposition->setOfficielle(false);
        foreach($composition as $idRole)
        {
            $role = $repository->find($idRole);
            $newComposition->addRoleCompo($role);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($newComposition);
        $em->flush();

        return false;

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
        return new Response(json_encode(array("min"=>$min,"max"=>$max)));
    }
} 