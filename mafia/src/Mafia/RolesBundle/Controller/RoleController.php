<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 16/01/2015
 * Time: 16:08
 */

namespace Mafia\RolesBundle\Controller;

use Mafia\RolesBundle\Entity\FactionEnum;
use Mafia\RolesBundle\Entity\Role;
use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleController extends Controller
{

    public function creationRoleAction()
    {
        $role = new Role();
        $formBuilder = $this->createFormBuilder($role);
        $formBuilder
            ->add('nomRole', 'text', array('label' => 'Nom du role'))
            ->add('description', 'textarea', array('label' => 'Description du role'))
            ->add('enum_role','choice',array('label' => 'Pouvoir du role','choices' => array_flip(RolesEnum::getNomsPouvoirs())))
            ->add('enum_faction','choice',array('label' => 'Faction du role','choices' => array_flip(FactionEnum::getNomsFactions())))
            ->add('roleUnique', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Role unique'))
            ->add('categoriesRole', 'entity', array('label' => 'Categories du role', 'class' => 'MafiaRolesBundle:Categorie'
                                                    , 'multiple' => 'true', 'expanded' => 'true'))
            ->add('crimesRole', 'entity', array('label' => 'Crimes du role', 'class' => 'MafiaRolesBundle:Crime'
                                                    , 'multiple' => 'true', 'expanded' => 'true'))
            ->add('Valider', 'submit');

        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();
            return $this->redirect($this->generateUrl('vue_role', array('id'=>$role->getId())));
        }
        return $this->render('MafiaRolesBundle:Formulaires:creer_role.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function affichageRoleAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');

        $role = $repository->find($id);
        return $this->render('MafiaRolesBundle:Affichages:vue_role.html.twig', array(
            'role' => $role
        ));
    }

    public function affichageListeRolesAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaRolesBundle:Role');

        $roles = $repository->findAll();
        return $this->render('MafiaRolesBundle:Affichages:liste_roles.html.twig', array(
            'roles' => $roles
        ));
    }
} 