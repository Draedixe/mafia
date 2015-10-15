<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/10/2015
 * Time: 19:44
 */

namespace Mafia\MessageBundle\Controller;

use Mafia\MessageBundle\Entity\MessagePrive;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

class MessageController extends Controller{

    function creationMessageAction($id)
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $recepteur = $repositoryUser->find($id);

        $message = new MessagePrive();
        $formBuilder = $this->createFormBuilder($message);
        $formBuilder
            ->add('texte', 'textarea',array('label'=>'Votre message : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setExpediteur($this->getUser());
            $message->setRecepteur($recepteur);
            $message->setDateEnvoi(new \DateTime());
            $message->setVu(false);
            $em->persist($message);
            $em->flush();
            return $this->redirect($this->generateUrl('profil',array('id' => $this->getUser()->getId())));
        }

        return $this->render('MafiaMessageBundle:Formulaires:creer_message.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    function affichageMessagesAction()
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaMessageBundle:MessagePrive');

        $envoyes = $repositoryUser->findBy(array("expediteur" => $this->getUser()));
        $recus = $repositoryUser->findBy(array("recepteur" => $this->getUser()));

        return $this->render('MafiaMessageBundle:Affichages:liste_messages.html.twig', array(
            'envoyes' => $envoyes,
            'recus' => $recus
        ));
    }

} 