<?php

namespace Mafia\RolesBundle\Controller;

use Mafia\RolesBundle\Entity\RolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    public function nuitAction()
    {

        $request = $this->container->get('request');
        $idCible = $request->get('id');

        $userGlobal = $this->getUser();
        if($userGlobal != null){
            $user = $userGlobal->getUserCourant();
            if($user != null){
                $role = $user->getRole();

                switch($role->getEnumRole()){
                    case RolesEnum::ADMINISTRATEUR:
                        break;
                    case RolesEnum::AGENT:
                        $this->forward('MafiaRolesBundle:Mafia:agent', array('idCible' => $idCible));
                        break;
                    case RolesEnum::AMNESIQUE:
                        $this->forward('MafiaRolesBundle:Neutre:amnesique', array('idCible' => $idCible));
                        break;
                    case RolesEnum::AVANT_GARDE:
                        break;
                    case RolesEnum::AUDITEUR:
                        $this->forward('MafiaRolesBundle:Neutre:auditeur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::BOUFFON:
                        $this->forward('MafiaRolesBundle:Neutre:bouffon', array('idCible' => $idCible));
                        break;
                    case RolesEnum::BOURREAU:
                        $this->forward('MafiaRolesBundle:Neutre:bourreau', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CITOYEN:
                        $this->forward('MafiaRolesBundle:Ville:citoyen', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CONCIERGE:
                        $this->forward('MafiaRolesBundle:Mafia:concierge', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CONDUCTEUR_DE_BUS:
                        $this->forward('MafiaRolesBundle:Ville:conducteurDeBus', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CONSEILLER:
                        $this->forward('MafiaRolesBundle:Mafia:conseiller', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CONTREFACTEUR:
                        $this->forward('MafiaRolesBundle:Mafia:contrefacteur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CRIEUR:
                        $this->forward('MafiaRolesBundle:Ville:crieur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::CULTISTE:
                        $this->forward('MafiaRolesBundle:Neutre:cultiste', array('idCible' => $idCible));
                        break;
                    case RolesEnum::DETECTIVE:
                        $this->forward('MafiaRolesBundle:Ville:detective', array('idCible' => $idCible));
                        break;
                    case RolesEnum::DOCTEUR:
                        $this->forward('MafiaRolesBundle:Ville:docteur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::DRAGON:

                        break;
                    case RolesEnum::ESCORT:
                        $this->forward('MafiaRolesBundle:Ville:escort', array('idCible' => $idCible));
                        break;
                    case RolesEnum::ESPION:
                        $this->forward('MafiaRolesBundle:Ville:espion', array('idCible' => $idCible));
                        break;
                    case RolesEnum::FAUSSAIRE:
                        $this->forward('MafiaRolesBundle:Mafia:faussaire', array('idCible' => $idCible));
                        break;
                    case RolesEnum::GARDE_DU_CORPS:
                        $this->forward('MafiaRolesBundle:Ville:gardeDuCorps', array('idCible' => $idCible));
                        break;
                    case RolesEnum::GARDIEN_DE_PRISON:
                        $this->forward('MafiaRolesBundle:Ville:gardienDePrison', array('idCible' => $idCible));
                        break;
                    case RolesEnum::GOUROU:
                        $this->forward('MafiaRolesBundle:Neutre:gourou', array('idCible' => $idCible));
                        break;
                    case RolesEnum::IMPOSTEUR:
                        $this->forward('MafiaRolesBundle:Mafia:imposteur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::INFORMATEUR:
                        break;
                    case RolesEnum::INSPECTEUR:
                        $this->forward('MafiaRolesBundle:Ville:inspecteur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::JUGE:
                        $this->forward('MafiaRolesBundle:Neutre:juge', array('idCible' => $idCible));
                        break;
                    case RolesEnum::KIDNAPPER:
                        $this->forward('MafiaRolesBundle:Mafia:kidnapper', array('idCible' => $idCible));
                        break;
                    case RolesEnum::LIAISON:
                        break;
                    case RolesEnum::INTERROGATEUR:
                        break;
                    case RolesEnum::JUSTICIER:
                        $this->forward('MafiaRolesBundle:Ville:justicier', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MACON:
                        $this->forward('MafiaRolesBundle:Ville:macon', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MACON_CHEF:

                        break;
                    case RolesEnum::MAFIOSO:
                        $this->forward('MafiaRolesBundle:Mafia:mafioso', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MAIRE:
                        $this->forward('MafiaRolesBundle:Ville:maire', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MAITRE_CHANTEUR:
                        $this->forward('MafiaRolesBundle:Mafia:maitreChanteur', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MAITRE_DU_DEGUISEMENT:
                        $this->forward('MafiaRolesBundle:Mafia:maitreDuDeguisement', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MAITRE_SILENCE:
                        break;
                    case RolesEnum::MAITRE_ENCENS:
                        break;
                    case RolesEnum::MARIONETTISTE:
                        $this->forward('MafiaRolesBundle:Neutre:marionettiste', array('idCible' => $idCible));
                        break;
                    case RolesEnum::MARSHALL:
                        break;
                    case RolesEnum::MEDECIN_LEGISTE:
                        $this->forward('MafiaRolesBundle:Ville:medecinLegiste', array('idCible' => $idCible));
                        break;
                    case RolesEnum::PARRAIN:

                        $this->forward('MafiaRolesBundle:Mafia:mafioso', array('idCible' => $idCible));
                        break;
                    case RolesEnum::PILLEUR:
                        break;
                    case RolesEnum::PROSTITUEE:
                        break;
                    case RolesEnum::PYROMANE:
                        break;
                    case RolesEnum::SHERIFF:
                        break;
                    case RolesEnum::SURVIVANT:
                        break;
                    case RolesEnum::TUEUR_A_GAGE:
                        break;
                    case RolesEnum::TUEUR_DE_MASSE:
                        break;
                    case RolesEnum::TUEUR_EN_SERIE:
                        break;
                    case RolesEnum::VEILLEUR:
                        break;
                    case RolesEnum::VETERAN:
                        break;
                }

            }
        }
        return new JsonResponse();
    }
}
