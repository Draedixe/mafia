<?php

namespace Mafia\ModerationBundle\Controller;

use Mafia\ModerationBundle\Entity\Bannissement;
use Mafia\UserBundle\Entity\regroupementVariable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BannissementController extends Controller
{

    public function listeBannissementsAction($page){
        $repositoryBans = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaModerationBundle:Bannissement');
        $bans = $repositoryBans->findAll();

        $nbBans = count($bans);
        if($nbBans%regroupementVariable::NB_BANS_PAR_PAGE_TOUT > 0){
            $nbPages = floor($nbBans/regroupementVariable::NB_BANS_PAR_PAGE_TOUT) +1;
        }
        else{
            $nbPages = $nbBans/regroupementVariable::NB_BANS_PAR_PAGE_TOUT;
        }
        if($nbPages == 1){
            return $this->render('MafiaModerationBundle:Affichages:liste_bans.html.twig', array(
                'bans' => $bans,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
        else{
            $bansSurPage = array_slice($bans,( regroupementVariable::NB_BANS_PAR_PAGE_TOUT * ($page-1)),regroupementVariable::NB_BANS_PAR_PAGE_TOUT);
            return $this->render('MafiaModerationBundle:Affichages:liste_bans.html.twig', array(
                'bans' => $bansSurPage,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }

    }

    public function listeBannissementsUserAction($id,$page){
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');
        $user = $repositoryUser->find($id);

        $repositoryBans = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaModerationBundle:Bannissement');

        $bans = $repositoryBans->findBy(array("userBanni" => $user));
        $nbBans = count($bans);
        if($nbBans%regroupementVariable::NB_BANS_PAR_PAGE_USER > 0){
            $nbPages = floor($nbBans/regroupementVariable::NB_BANS_PAR_PAGE_USER) +1;
        }
        else{
            $nbPages = $nbBans/regroupementVariable::NB_BANS_PAR_PAGE_USER;
        }
        if($nbPages == 1){
            return $this->render('MafiaModerationBundle:Affichages:liste_bans_user.html.twig', array(
                'user' => $user,
                'bans' => $bans,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }
        else{
            $bansSurPage = array_slice($bans,( regroupementVariable::NB_BANS_PAR_PAGE_USER * ($page-1)),regroupementVariable::NB_BANS_PAR_PAGE_USER);
            return $this->render('MafiaModerationBundle:Affichages:liste_bans_user.html.twig', array(
                'user' => $user,
                'bans' => $bansSurPage,
                'pageCourante' => $page,
                'nbPages' => $nbPages
            ));
        }

    }
}