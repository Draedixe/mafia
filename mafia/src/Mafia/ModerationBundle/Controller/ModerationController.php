<?php

namespace Mafia\ModerationBundle\Controller;

use Mafia\UserBundle\Entity\regroupementVariable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mafia\ModerationBundle\Entity\Bannissement;

class ModerationController extends Controller
{

    public function tableauModerationAction(){

        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();

        $qb->select('b')
            ->from('Mafia\ModerationBundle\Entity\Bannissement', 'b')
            ->orderBy('b.debutBannissement','DESC');

        $query = $qb->getQuery();

        $bans = $query->getResult();

        $derniersBans = array_slice($bans,0,regroupementVariable::NB_DERNIERS_BANS);

        $qbSurv = $em->createQueryBuilder();

        $qbSurv->select('s')
            ->from('Mafia\ModerationBundle\Entity\Surveillance', 's')
            ->where('s.termine = false')
            ->orderBy('s.priorite','ASC');

        $querySurv = $qbSurv->getQuery();

        $surveillances = $querySurv->getResult();

        return $this->render('MafiaModerationBundle:Affichages:tableau_moderation.html.twig', array(
            'derniersBans' => $derniersBans,
            'surveillances' => $surveillances
        ));
    }
    public function tableauAdministrationAction(){

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('pseudo', 'text', array('label' => 'Pseudo du joueur'))
                    ->add('role', 'choice', array(
                        'choices' => array('USER' => 'Utilisateur', 'ROLE_MODERATEUR' => 'Modérateur','ROLE_SUPER_MODERATEUR' => 'Super Modérateur', 'ROLE_ADMIN' => 'Administrateur'),
                        'multiple' => false
                    ));

        $form = $formBuilder->getForm();
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {

            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaUserBundle:User');

            $user = $repositoryUser->findOneBy(array('username' => $form->get('pseudo')->getData()));
            $role = $form->get('role')->getData();
            if($role != 'USER'){
                $roles = array();
                $roles[] = $role;
                $user->setRoles($roles);
            }
            else{
                $user->setRoles(array());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('tableau_administration', array()));
        }
        return $this->render('MafiaModerationBundle:Affichages:tableau_administration.html.twig', array(
            'promotion' => $form->createView()
        ));
    }
}