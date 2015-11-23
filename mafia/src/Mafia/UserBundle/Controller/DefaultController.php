<?php

namespace Mafia\UserBundle\Controller;

use Mafia\ModerationBundle\Entity\Bannissement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

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
        if ($this->get('security.context')->isGranted('ROLE_MODERATEUR')) {
            if ($user != null) {
                $bannissement = new Bannissement();
                $formBuilder = $this->createFormBuilder($bannissement);
                $formBuilder
                    ->add('explication', 'textarea', array('label' => 'Explication : '))
                    ->add('tempsBannissement', 'integer', array('label' => 'Temps de ban : '))
                    ->add('unite', 'choice', array(
                        'choices' => array('S' => 'Secondes', 'I' => 'Minutes', 'H' => 'Heures', 'D' => 'Jours', 'M' => 'Mois', 'F' => 'Définitif'),
                        'multiple' => false
                        ));
                $form = $formBuilder->getForm();
                $request = $this->get('request');

                if ($request->getMethod() == 'POST') {
                    $form->bind($request);
                }
                if ($form->isValid()) {
                    $bannissement->setDebutBannissement(new \DateTime());
                    $bannissement->setModoBannant($this->getUser());

                    $bannissement->setUserBanni($user);

                    $user->addRole("ROLE_BANNI");
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->persist($bannissement);
                    $em->flush();
                    return $this->redirect($this->generateUrl('tableau_moderation', array()));
                }
                return $this->render('MafiaUserBundle:Affichages:vue_user.html.twig',
                    array(
                        "user" => $user,
                        "form" => $form->createView()
                    )
                );
            } else {
                return $this->redirect($this->generateUrl('menu_principal', array()));
            }
        }
        else{
            if ($user != null) {
                return $this->render('MafiaUserBundle:Affichages:vue_user.html.twig',
                    array(
                        "user" => $user
                    )
                );
            } else {
                return $this->redirect($this->generateUrl('menu_principal', array()));
            }
        }
    }
}
