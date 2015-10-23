<?php

namespace Mafia\ModerationBundle\Controller;

use Mafia\ModerationBundle\Entity\Requete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class RequeteController extends Controller
{

    public function creationRequeteAction()
    {
        $requete = new Requete();
        $formBuilder = $this->createFormBuilder($requete);
        $formBuilder
            ->add('titreRequete', 'text',array('label'=>'Titre de votre requête : '))
            ->add('descriptionRequete', 'textarea',array('label'=>'Votre requete : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $requete->setRequeteur($this->getUser());
            $requete->setEstReponse(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($requete);
            $em->flush();
            return $this->redirect($this->generateUrl('vos_requetes',array()));
        }

        return $this->render('MafiaModerationBundle:Formulaires:creer_requetes.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listeVosRequetesAction(){

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaModerationBundle:Requete');

        $requetes = $repository->findBy(array('requeteur' => $this->getUser()));
        return $this->render('MafiaModerationBundle:Affichages:liste_vos_requetes.html.twig', array(
            'requetes' => $requetes
        ));
    }

    public function listeRequetesEnCoursAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_MODERATEUR')) {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaModerationBundle:Requete');

            $requetes = $repository->findBy(array('reponse' => null));

            return $this->render('MafiaModerationBundle:Affichages:liste_requetes_ouvertes.html.twig', array(
                'requetes' => $requetes
            ));
        }
        else{
            return $this->redirect($this->generateUrl('vos_requetes',array()));
        }
    }
}