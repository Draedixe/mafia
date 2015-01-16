<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 16/01/2015
 * Time: 16:15
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Crime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CrimeController extends Controller{

    public function creationCrimeAction()
    {
        $crime = new Crime();
        $formBuilder = $this->createFormBuilder($crime);
        $formBuilder
            ->add('nomCrime', 'text',array('label'=>'Nom du crime : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($crime);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_crimes',array()));
        }

        return $this->render('MafiaRolesBundle:Formulaires:creer_crime.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function affichageListeCrimesAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Crime');

        $crimes = $repository->findAll();
        return $this->render('MafiaRolesBundle:Affichages:liste_crimes.html.twig', array(
            'crimes' => $crimes
        ));
    }
} 