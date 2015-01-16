<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 16/01/2015
 * Time: 17:30
 */

namespace Mafia\RolesBundle\Controller;


use Mafia\RolesBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategorieController extends Controller{

    public function creationCategorieAction()
    {
        $categorie = new Categorie();
        $formBuilder = $this->createFormBuilder($categorie);
        $formBuilder
            ->add('nomCategorie', 'text',array('label'=>'Nom de la catégorie : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirect($this->generateUrl('liste_categories',array()));
        }

        return $this->render('MafiaRolesBundle:Formulaires:creer_categorie.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function affichageListeCategoriesAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Categorie');

        $categories = $repository->findAll();
        return $this->render('MafiaRolesBundle:Affichages:liste_categories.html.twig', array(
            'categories' => $categories
        ));
    }

} 