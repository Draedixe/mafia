<?php

namespace Mafia\UserBundle\Component\Authentication;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    protected $router;
    protected $em;

    public function __construct(Router $router,EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $repository = $this->em->getRepository('MafiaModerationBundle:Bannissement');

        $bannissementsCourants = $repository->findBy(array("userBanni" => $token->getUser()));

        $banDef = $repository->findBy(array("userBanni" => $token->getUser(),"unite" => "F"));

        if($banDef != null)
        {
            return new RedirectResponse($this->router->generate('fos_user_security_logout', array()));
        }
        //actuellement banni
        if($bannissementsCourants != null){
            $date = new \DateTime();
            foreach($bannissementsCourants as $ban)
            {
                if($ban->getUnite() == "D" || $ban->getUnite() == "M" || $ban->getUnite() == "Y"){
                    if($ban->getDebutBannissement()->add(new \DateInterval('P' . $ban->getTempsBannissement() . $ban->getUnite() )) > $date) {
                        return new RedirectResponse($this->router->generate('fos_user_security_logout', array()));
                    }
                }
                elseif($ban->getUnite() == "I"){
                    if($ban->getDebutBannissement()->add(new \DateInterval('PT' . $ban->getTempsBannissement() . 'M' )) > $date){
                        return new RedirectResponse($this->router->generate('fos_user_security_logout', array()));
                    }
                }
                else{
                    if($ban->getDebutBannissement()->add(new \DateInterval('PT' . $ban->getTempsBannissement() . $ban->getUnite() )) > $date){
                        return new RedirectResponse($this->router->generate('fos_user_security_logout', array()));
                    }
                }
            }
            return new RedirectResponse($this->router->generate('mafia_user_homepage', array()));
        }else{
            return new RedirectResponse($this->router->generate('mafia_user_homepage', array()));
        }

    }

}