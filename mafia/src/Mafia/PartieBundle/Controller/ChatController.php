<?php

namespace Mafia\PartieBundle\Controller;

use Mafia\PartieBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends Controller{

    public function envoyerMessageAction(){
        $request = $this->container->get('request');
        $message = $request->get('message');

        $repositoryMessage = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:Message');
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaPartieBundle:UserPartie');
        $em = $this->getDoctrine()->getManager();


        $user = $repositoryUser->findOneBy(array("user" => $this->getUser()));
        $partie = $user->getPartie();
        $chat = $partie->getChat();

        $newMessage = new Message();
        $newMessage->setChat($chat);
        $newMessage->setDate(new \DateTime());
        $newMessage->setTexte($message);
        $newMessage->setUser($this->getUser());


        $em->persist($newMessage);
        $em->flush();

        $response = new JsonResponse(array('name' => $message, 'user' => $user->getId()));

        return $response;

    }
}