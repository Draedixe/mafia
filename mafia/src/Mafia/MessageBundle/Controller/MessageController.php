<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/10/2015
 * Time: 19:44
 */

namespace Mafia\MessageBundle\Controller;

use Mafia\MessageBundle\Entity\MessagePrive;
use Mafia\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

class MessageController extends Controller{

    private static $nbElemParPage = 1;

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
            $recepteur->ajoutMessageNonLu();
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


    function affichageMessagesAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT DISTINCT u FROM Mafia\UserBundle\Entity\User u, Mafia\MessageBundle\Entity\MessagePrive m WHERE m.recepteur = :idRecepteur AND m.expediteur = u OR m.recepteur = u AND m.expediteur = :idExpediteur2');
        $query->setParameters(array(
            'idRecepteur' => $this->getUser(),
            'idExpediteur2' => $this->getUser(),
        ));
        $liste_contacts = $query->getResult();
        $this->getUser()->setNbMessagesNonLus(0);
        $messages = array();
        foreach($liste_contacts as $contact)
        {
            $query = $em->createQuery('SELECT m FROM Mafia\MessageBundle\Entity\MessagePrive m WHERE m.recepteur = :idRecepteur AND m.expediteur = :idExpediteur OR m.recepteur = :idRecepteur2 AND m.expediteur = :idExpediteur2');
            $query->setParameters(array(
                'idRecepteur' => $this->getUser(),
                'idExpediteur' => $contact,
                'idRecepteur2' => $contact,
                'idExpediteur2' => $this->getUser(),
            ));

            $messages[$contact->getUsername()] = $query->getResult();
        }

        $nbContacts = count($liste_contacts);
        if($nbContacts%self::$nbElemParPage > 0){
            $nbPages = floor($nbContacts/self::$nbElemParPage) +1;
        }
        else{
            $nbPages = $nbContacts/self::$nbElemParPage;
        }
        if($nbPages == 1){
            return $this->render('MafiaMessageBundle:Affichages:liste_messages.html.twig', array(
                'messages' => $messages,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
        else{
            $messagesSurPage = array_slice($messages,( self::$nbElemParPage * ($page-1)),self::$nbElemParPage);
            return $this->render('MafiaMessageBundle:Affichages:liste_messages.html.twig', array(
                'messages' => $messagesSurPage,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }

    }

    function repondreMessageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $id = $request->get("id");

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $recepteur = $repositoryUser->find($id);

        $message = new MessagePrive();
        $message->setTexte($request->get("message"));
        $message->setExpediteur($this->getUser());
        $message->setRecepteur($recepteur);
        $recepteur->ajoutMessageNonLu();
        $message->setDateEnvoi(new \DateTime());
        $message->setVu(false);
        $em->persist($message);
        $em->flush();
        return new JsonResponse(array("statut" => "SUCCESS"));
    }

} 