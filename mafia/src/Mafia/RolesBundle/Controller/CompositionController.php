<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 18/01/2015
 * Time: 19:11
 */

namespace Mafia\RolesBundle\Controller;


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

} 