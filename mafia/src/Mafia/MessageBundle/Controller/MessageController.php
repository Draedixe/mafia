<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 15/10/2015
 * Time: 19:44
 */

namespace Mafia\MessageBundle\Controller;

use Mafia\MessageBundle\Entity\MessagePrive;
use Mafia\UserBundle\Entity\regroupementVariable;
use Mafia\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;

class MessageController extends Controller{


    public function creationMessageAction($id)
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
    public function affichageContactsAction($page){
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT DISTINCT u FROM Mafia\UserBundle\Entity\User u, Mafia\MessageBundle\Entity\MessagePrive m WHERE m.recepteur = :idRecepteur AND m.expediteur = u OR m.recepteur = u AND m.expediteur = :idExpediteur');
        $query->setParameters(array(
            'idRecepteur' => $this->getUser(),
            'idExpediteur' => $this->getUser(),
        ));
        $liste_contacts = $query->getResult();

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaMessageBundle:MessagePrive');

        $nbMessagesNonLus = array();

        foreach($liste_contacts as $contact){
            $messagesNonLu = $repositoryMessage->findBy(array('expediteur'=>$contact,'vu' => false));
            $nbMessagesNonLus[$contact->getId()] = count($messagesNonLu);
        }

        $nbContacts = count($liste_contacts);
        if($nbContacts%regroupementVariable::NB_CONTACTS_PAR_PAGE > 0){
            $nbPages = floor($nbContacts/regroupementVariable::NB_CONTACTS_PAR_PAGE) +1;
        }
        else{
            $nbPages = $nbContacts/regroupementVariable::NB_CONTACTS_PAR_PAGE;
        }
        if($nbPages == 1){
            return $this->render('MafiaMessageBundle:Affichages:liste_contacts.html.twig', array(
                'nbMessagesNL' => $nbMessagesNonLus,
                'contacts' => $liste_contacts,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
        else{
            $contactsSurPage = array_slice($liste_contacts,( regroupementVariable::NB_CONTACTS_PAR_PAGE * ($page-1)),regroupementVariable::NB_CONTACTS_PAR_PAGE);
            return $this->render('MafiaMessageBundle:Affichages:liste_contacts.html.twig', array(
                'nbMessagesNL' => $nbMessagesNonLus,
                'contacts' => $contactsSurPage,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
    }

    public function affichageMessagesAction($id,$page)
    {

        $em = $this->getDoctrine()->getManager();

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $contact = $repositoryUser->find($id);

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaMessageBundle:MessagePrive');

        $messagesNonLu = $repositoryMessage->findBy(array('expediteur'=>$contact,'vu' => false));

        $this->getUser()->setNbMessagesNonLus($this->getUser()->getNbMessagesNonLus()-count($messagesNonLu));
        foreach($messagesNonLu as $message)
        {
            $message->setVu(true);
            $em->persist($message);
        }
        $em->persist($this->getUser());
        $em->flush();

        $query = $em->createQuery('SELECT m FROM Mafia\MessageBundle\Entity\MessagePrive m WHERE m.recepteur = :idRecepteur AND m.expediteur = :idExpediteur OR m.recepteur = :idRecepteur2 AND m.expediteur = :idExpediteur2');
        $query->setParameters(array(
            'idRecepteur' => $this->getUser(),
            'idExpediteur' => $contact,
            'idRecepteur2' => $contact,
            'idExpediteur2' => $this->getUser(),
        ));
        $messages = $query->getResult();

        $nbMessages = count($messages);
        if($nbMessages%regroupementVariable::NB_MP_PAR_PAGE > 0){
            $nbPages = floor($nbMessages/regroupementVariable::NB_MP_PAR_PAGE) +1;
        }
        else{
            $nbPages = $nbMessages/regroupementVariable::NB_MP_PAR_PAGE;
        }
        if($nbPages == 1){
            return $this->render('MafiaMessageBundle:Affichages:liste_messages.html.twig', array(
                'contact' => $contact,
                'messages' => $messages,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
        else{
            $messagesSurPage = array_slice($messages,( regroupementVariable::NB_MP_PAR_PAGE * ($page-1)),regroupementVariable::NB_MP_PAR_PAGE);
            return $this->render('MafiaMessageBundle:Affichages:liste_messages.html.twig', array(
                'contact' => $contact,
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