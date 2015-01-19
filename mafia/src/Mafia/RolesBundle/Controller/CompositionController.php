<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 18/01/2015
 * Time: 19:11
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Composition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        return $this->redirect($this->generateUrl('vue_role', array('id'=>1)));

    }
} 