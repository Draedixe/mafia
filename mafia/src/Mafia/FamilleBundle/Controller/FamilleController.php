<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 29/01/2015
 * Time: 22:43
 */

namespace Mafia\FamilleBundle\Controller;


use Mafia\FamilleBundle\Entity\DemandeEntree;
use Mafia\FamilleBundle\Entity\Famille;
use Mafia\FamilleBundle\Entity\Proposition;
use Mafia\PartieBundle\Entity\Chat;
use Mafia\PartieBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

class FamilleController extends Controller{

    public function creationFamilleAction()
    {
        if($this->getUser()->getFamille() == null){
            $famille = new Famille();
            $formBuilder = $this->createFormBuilder($famille);
            $formBuilder
                ->add('nom', 'text',array('label'=>'Nom de la famille : '))
                ->add('description', 'textarea',array('label'=>'Description de la famille : '));
            $form = $formBuilder->getForm();
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
                $form->bind($request);
            }
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $famille->setChef($this->getUser());
                $chat = new Chat();
                $em->persist($chat);
                $famille->setChat($chat);

                $em->persist($famille);
                $this->getUser()->setFamille($famille);
                $em->persist($this->getUser());
                $em->flush();
                return $this->redirect($this->generateUrl('vue_famille',array("id" => $famille->getId())));
            }

            return $this->render('MafiaFamilleBundle:Formulaires:creer_famille.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        else
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
        }
    }

    public function affichageFamilleAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaFamilleBundle:Famille');

        $famille = $repository->find($id);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');

        $membres = $repository->findBy(array('famille'=>$famille));

        $message = new Message();
        $formBuilder = $this->createFormBuilder($message);
        $formBuilder
            ->add('texte', 'text',array('label'=>'Taper ici : '));
        $form = $formBuilder->getForm();
        $request = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
        }
        if ($form->isValid()) {
            if($this->getUser()->getFamille()->getId() == $famille->getId()) {
                $em = $this->getDoctrine()->getManager();
                $message->setDate(new \DateTime());
                $message->setUser($this->getUser());
                $message->setChat($famille->getChat());

                $em->persist($message);
                $em->flush();
            }
        return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
        }
        return $this->render('MafiaFamilleBundle:Affichages:vue_famille.html.twig', array(
            'famille' => $famille,
            'membres' => $membres,
            'form' => $form->createView()
        ));
    }

    public function creationDemandeEntreeAction($id)
    {
        if($this->getUser()->getFamille() != null)
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
        }
        else
        {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaFamilleBundle:Famille');

            $famille = $repository->find($id);
            $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaFamilleBundle:DemandeEntree');
            $demande = $repository->findOneBy(array("demandeur"=>$this->getUser(),"familleDemandee" => $famille));
            if($demande == null)
            {
                $demande = new DemandeEntree();
            }
            $formBuilder = $this->createFormBuilder($demande);
            $formBuilder
                ->add('messageDemande', 'textarea',array('label'=>'Pourquoi voulez-vous rejoindre la famille?'));
            $form = $formBuilder->getForm();
            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {
                $form->bind($request);
            }
            if ($form->isValid()) {
                if($this->getUser()->getFamille() == null) {
                    $em = $this->getDoctrine()->getManager();
                    $demande->setDateDemande(new \DateTime());
                    $demande->setDemandeur($this->getUser());
                    $demande->setFamilleDemandee($famille);

                    $em->persist($demande);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('vue_famille',array("id" => $id)));
            }
            return $this->render('MafiaFamilleBundle:Formulaires:creer_demandeEntree.html.twig', array(
                'form' => $form->createView()
            ));
        }

    }

    public function choixDemandeAction()
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $repositoryDemande = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaFamilleBundle:DemandeEntree');
            $demande = $repositoryDemande->find($request->get("idDemande"));
            if($demande != null)
            {
                if($this->getUser()->getId() == $demande->getFamilleDemandee()->getChef()->getId())
                {
                    $em = $this->getDoctrine()->getManager();
                    if($request->get("accepter") == "oui")
                    {
                        $demande->getDemandeur()->setFamille($demande->getFamilleDemandee());
                        $em->persist($demande->getDemandeur());
                    }
                    $em->remove($demande);
                    $em->flush();
                }
            }

        }
        return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
    }

    public function choixPropositionAction()
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $repositoryProposition = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaFamilleBundle:Proposition');
            $proposition = $repositoryProposition->find($request->get("idProposition"));
            if($proposition != null)
            {
                if($this->getUser()->getId() == $proposition->getUserPropose()->getId())
                {
                    $em = $this->getDoctrine()->getManager();
                    if($request->get("accepter") == "oui")
                    {
                        $this->getUser()->setFamille($proposition->getFamilleProposante());
                        $em->persist($this->getUser());
                        $propositions = $repositoryProposition->findBy(array("userPropose" => $this->getUser()));
                        foreach($propositions as $propositionUser)
                        {
                            $em->remove($propositionUser);
                        }
                        $em->flush();
                    }
                    elseif($request->get("accepter") == "non")
                    {
                        $em->remove($proposition);
                        $em->flush();
                    }
                }
            }
        }
        if($request->get("accepter") == "oui")
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
        }
        else
        {
            return $this->redirect($this->generateUrl('liste_propositions'));
        }

    }

    public function annulerPropositionAction($id)
    {
            $repositoryProposition = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaFamilleBundle:Proposition');
            $proposition = $repositoryProposition->find($id);
            if($proposition != null)
            {
                if($this->getUser()->getId() == $proposition->getFamilleProposante()->getChef()->getId())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($proposition);
                    $em->flush();
                }
            }

        return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
    }

    public function affichageListePropositionsAction()
    {
        return $this->render('MafiaFamilleBundle:Affichages:liste_propositions.html.twig');
    }

    public function creationPropositionAction()
    {
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $repositoryUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('MafiaUserBundle:User');
            $user = $repositoryUser->findOneBy(array("username" => $request->get("pseudoUser")));
            if($user != null && $user->getFamille() == null)
            {
                if($this->getUser()->getFamille() != null && $this->getUser()->getId() == $this->getUser()->getFamille()->getChef()->getId())
                {
                    $repositoryProposition = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MafiaFamilleBundle:Proposition');
                    $proposition = $repositoryProposition->findOneBy(array("userPropose" => $user , "familleProposante" => $this->getUser()->getFamille()));
                    if($proposition == null)
                    {
                        $proposition = new Proposition();
                        $proposition->setUserPropose($user);
                        $proposition->setFamilleProposante($this->getUser()->getFamille());
                        $proposition->setDateProposition(new \DateTime());
                    }
                    $proposition->setMessageProposition($request->get("messageProposition"));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($proposition);
                    $em->flush();
                }
            }

        }
        if($this->getUser()->getFamille() != null)
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
        }
        else
        {
            return $this->redirect($this->generateUrl('liste_proposition'));
        }

    }

    public function virerMembreAction($id)
    {
        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaUserBundle:User');
        $user = $repositoryUser->find($id);

        if($user != null && $this->getUser()->getId() != $id)
        {
            if($user->getFamille() != null ){
                if($user->getFamille()->getChef()->getId() == $this->getUser()->getId())
                {
                    $user->setFamille(null);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                }

            }
        }
        if($this->getUser()->getFamille() != null)
        {
            return $this->redirect($this->generateUrl('vue_famille',array("id" => $this->getUser()->getFamille()->getId())));
        }
        else
        {
            return $this->render('MafiaFamilleBundle:Affichages:liste_propositions.html.twig');
        }
    }

    public function quitterFamilleAction()
    {
        $user = $this->getUser();
        $famille = $user->getFamille();

        if($famille != null)
        {
            $em = $this->getDoctrine()->getManager();
            if($famille->getChef()->getId() == $user->getId())
            {
                if(count($famille->getMembres()) <= 1)
                {
                    $em->remove($famille);
                }
                else
                {
                    if($famille->getMembres()->first()->getId() != $user->getId())
                    {
                        $famille->setChef($famille->getMembres()->first());
                    }
                    else
                    {
                        $famille->setChef($famille->getMembres()->next());
                    }
                }
            }
                $user->setFamille(null);
                $em->persist($user);
                $em->flush();

        }
        return $this->redirect($this->generateUrl('mafia_user_homepage'));
    }

    public function affichageFamillesAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('MafiaFamilleBundle:Famille');

        $familles = $repository->findAll();
        return $this->render('MafiaFamilleBundle:Affichages:liste_familles.html.twig', array(
            'familles' => $familles
        ));
    }
} 