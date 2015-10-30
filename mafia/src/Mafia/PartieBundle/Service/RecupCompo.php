<?php

namespace Mafia\PartieBundle\Service;

class RecupCompo
{
    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function recupCompo($user) {
        $partie = $user->getPartie();
        $compo = $partie->getComposition();
        $roles = $compo->getRolesCompo();
        $rolesData = array();
        //récupération ROLES FIXES
        foreach($roles as $r){
            for($i = 0; $i<$r->getQuantite();$i++) {
                array_push($rolesData, $r->getRole()->getNomRole());
            }
        }
        //récupération ROLES ALEATOIRES (CATOGIRES)
        $categories = $compo->getCategoriesCompo();
        foreach($categories as $r){
            for($i = 0; $i<$r->getQuantite();$i++) {
                array_push($rolesData, $r->getCategorie()->getNomCategorie());
            }
        }
        return $rolesData;

    }
}