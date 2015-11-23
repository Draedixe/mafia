<?php

namespace Mafia\ModerationBundle\Controller;

use Mafia\ModerationBundle\Entity\Surveillance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SurveillanceController extends Controller
{
    public function creationSurveillanceAction(){
        $surveillance = new Surveillance();
        $formBuilder = $this->createFormBuilder($surveillance);
        $formBuilder
            ->add('intitule', 'text',array('label'=>'Intitulé de la surveillance : '))
            ->add('raison', 'textarea',array('label'=>'Raison de la surveillance : '))
            ->add('premierSurveille', 'text', array('label'=>'Pseudo du premier surveillé','mapped'=>false))
            ->add('priorite', 'integer',array('label'=>'Priorité : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST'){
            $form->bind($request);
        }
        if ($form->isValid()) {
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaUserBundle:User');

            $userSurveille = $repositoryUser->findOneBy(array('username' => $form->get('premierSurveille')->getData()));
            if($userSurveille == null){

            }
            else{
                $surveillance->setCreateur($this->getUser());
                $surveillance->addUserSurveille($userSurveille);
                $em = $this->getDoctrine()->getManager();
                $em->persist($surveillance);
                $em->flush();
                return $this->redirect($this->generateUrl('vue_surveillance',array('id'=>$surveillance->getId())));
            }
        }

        return $this->render('MafiaModerationBundle:Formulaires:creer_surveillance.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function ajouterUserEnSurveillance(){
        $request = $this->get('request');
        $idUser = $request->get("idUser");
        $idSurveillance = $request->get("idSurveillance");

    }

    public function affichageSurveillanceAction($id){
        $repositorySurveillance = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaModerationBundle:Surveillance');

        $surveillance = $repositorySurveillance->find($id);

        return $this->render('MafiaModerationBundle:Affichages:vue_surveillance.html.twig', array(
            'surveillance' => $surveillance
        ));
    }

}