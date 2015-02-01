<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 29/01/2015
 * Time: 22:43
 */

namespace Mafia\FamilleBundle\Controller;


use Mafia\FamilleBundle\Entity\DemandeEntree;
use Mafia\FamilleBundle\Entity\Famille;
use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FamilleController extends Controller{

    public function creationFamilleAction()
    {
        if($this->getUser()->getFamille() == null){
            $famille = new Famille();
            $formBuilder = $this->createFormBuilder($famille);
            $formBuilder
                ->add('nom', 'text',array('label'=>'Nom de la famille : '))
                ->add('description', 'textarea',array('label'=>'Description de la famille : '));
            $form = $formBuilder->getForm();
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
                $form->bind($request);
            }
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $famille->setChef($this->getUser());
                $chat = new Chat();
                $em->persist($chat);
                $famille->setChat($chat);

                $em->persist($famille);
                $this->getUser()->setFamille($famille);
                $em->persist($this->getUser());
                $em->flush();
                return $this->redirect($this->generateUrl('vue_famille',array("id" => $famille->getId())));
            }

            return $this->render('MafiaFamilleBundle:Formulaires:creer_famille.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
        }
    }

    public function affichageFamilleAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaFamilleBundle:Famille');

        $famille = $repository->find($id);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $membres = $repository->findBy(array('famille'=>$famille));

        $message = new Message();
        $formBuilder = $this->createFormBuilder($message);
        $formBuilder
            ->add('texte', 'text',array('label'=>'Taper ici : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            if($this->getUser()->getFamille()->getId() == $famille->getId()) {
                $em = $this->getDoctrine()->getManager();
                $message->setDate(new \DateTime());
                $message->setUser($this->getUser());
                $message->setChat($famille->getChat());

                $em->persist($message);
                $em->flush();
            }
        return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
        }
        return $this->render('MafiaFamilleBundle:Affichages:vue_famille.html.twig', array(
            'famille' => $famille,
            'membres' => $membres,
            'form' => $form->createView()
        ));
    }

    public function creationDemandeEntreeAction($id)
    {
        if($this->getUser()->getFamille() != null)
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
        }
        else
        {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaFamilleBundle:Famille');

            $famille = $repository->find($id);
            $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaFamilleBundle:DemandeEntree');
            $demande = $repository->findOneBy(array("demandeur"=>$this->getUser(),"familleDemandee" => $famille));
            if($demande == null)
            {
                $demande = new DemandeEntree();
            }
            $formBuilder = $this->createFormBuilder($demande);
            $formBuilder
                ->add('messageDemande', 'textarea',array('label'=>'Pourquoi voulez-vous rejoindre la famille?'));
            $form = $formBuilder->getForm();
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
                $form->bind($request);
            }
            if ($form->isValid()) {
                if($this->getUser()->getFamille() == null) {
                    $em = $this->getDoctrine()->getManager();
                    $demande->setDateDemande(new \DateTime());
                    $demande->setDemandeur($this->getUser());
                    $demande->setFamilleDemandee($famille);

                    $em->persist($demande);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
            }
            return $this->render('MafiaFamilleBundle:Formulaires:creer_demandeEntree.html.twig', array(
                'form' => $form->createView()
            ));
        }

    }
} 