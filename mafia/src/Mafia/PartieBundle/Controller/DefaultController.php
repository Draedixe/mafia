<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\DebutPartieEnum;
use Mafia\PartieBundle\Entity\Parametres;
use Mafia\PartieBundle\Entity\TypeJugementEnum;
use Mafia\PartieBundle\Entity\TypeNuitEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\PartieBundle\Form\Type\RangeType;

class DefaultController extends Controller
{
    public function creationParametresAction()
    {
        $parametres = new Parametres();
        $formBuilder = $this->createFormBuilder($parametres);
        $formBuilder
            ->add('nomParametres', 'text', array('label' => 'Nom des paramètres'))
            ->add('dureeDuJour', new RangeType(), array('label' => 'Durée du jour'))
            ->add('enumTypeDeJugement', 'choice',array('label' => 'Type de jugement','choices' => array_flip(TypeJugementEnum::getTypeJugement())))
            ->add('dureeDeLaNuit', new RangeType(), array('label' => 'Durée de la nuit'))
            ->add('dernieresVolontes', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Dernières volontés'))
            ->add('tempsDeDiscussion', new RangeType(), array('label' => 'Temps de discussion: '))
            ->add('debutDuJeu', 'choice',array('label' => 'Début du jeu','choices' => array_flip(DebutPartieEnum::getDebutPartie())))
            ->add('typeDeNuit', 'choice',array('label' => 'Type de nuit','choices' => array_flip(TypeNuitEnum::getTypeNuit())))
            ->add('messagePrives', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Messages privés'))
            ->add('phaseDiscussion', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Phase de discussion'))
            ->add('choisirNom', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Choisir le nom'))
            ->add('officiel', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Officiel'))
            ->add('Confirmer', 'submit');
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parametres);
            $em->flush();
            return $this->redirect($this->generateUrl('vue_parametres', array('id'=>$parametres->getId())));
        }
        return $this->render('MafiaPartieBundle:Formulaires:creer_parametres.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function supprimerParametresAction($id)
    {
        $em = $this->getDoctrine() ->getManager();
        $repository = $em ->getRepository('MafiaPartieBundle:Parametres');

        $parametres = $repository->find($id);
        $em -> remove($parametres);
        $em -> flush();

        return $this->redirect($this->generateUrl('liste_parametres'));
    }

    public function affichageParametresAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Parametres');

        $parametres = $repository->find($id);

        $formBuilder = $this->createFormBuilder($parametres);
        $formBuilder
            ->add('nomParametres', 'text', array('label' => 'Nom des paramètres'))
            ->add('dureeDuJour', new RangeType(), array('label' => 'Durée du jour'))
            ->add('enumTypeDeJugement', 'choice',array('label' => 'Type de jugement','choices' => array_flip(TypeJugementEnum::getTypeJugement())))
            ->add('dureeDeLaNuit', new RangeType(), array('label' => 'Durée de la nuit'))
            ->add('dernieresVolontes', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Dernières volontés'))
            ->add('tempsDeDiscussion', new RangeType(), array('label' => 'Temps de discussion: '))
            ->add('debutDuJeu', 'choice',array('label' => 'Début du jeu','choices' => array_flip(DebutPartieEnum::getDebutPartie())))
            ->add('typeDeNuit', 'choice',array('label' => 'Type de nuit','choices' => array_flip(TypeNuitEnum::getTypeNuit())))
            ->add('messagePrives', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Messages privés'))
            ->add('phaseDiscussion', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Phase de discussion'))
            ->add('choisirNom', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Choisir le nom'))
            ->add('officiel', 'choice', array('choices'=>array(1 => 'Oui',0 => 'Non'),'label' => 'Officiel'))
            ->add('Modifier', 'submit');
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parametres);
            $em->flush();
            return $this->redirect($this->generateUrl('vue_parametres', array('id'=>$parametres->getId())));
        }
        return $this->render('MafiaPartieBundle:Formulaires:vue_parametres.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function affichageListeParametresAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Parametres');

        $parametres = $repository->findAll();
        return $this->render('MafiaPartieBundle:Affichages:liste_parametres.html.twig', array(
            'parametres' => $parametres
        ));
    }
}
