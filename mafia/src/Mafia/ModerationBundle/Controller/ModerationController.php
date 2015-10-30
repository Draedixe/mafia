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

        return $this->render('MafiaModerationBundle:Affichages:tableau_moderation.html.twig', array(
            'derniersBans' => $derniersBans
        ));
    }
}