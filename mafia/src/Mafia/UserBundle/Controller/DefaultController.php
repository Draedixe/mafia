<?php

namespace Mafia\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function menuAction()
    {
        return $this->render('MafiaUserBundle:Default:index.html.twig');
    }

    public function profilAction($id)
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $user = $repositoryUser->find($id);

        if($user != null)
        {
            return $this->render('MafiaUserBundle:Affichages:vue_user.html.twig',
                array(
                    "user" => $user
                )
            );
        }
        else
        {

        }
    }
}
