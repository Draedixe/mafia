<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RolesCompos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\RolesComposRepository")
 */
class RolesCompos
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Role")
     */
    private $role;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Composition")
     */
    private $composition;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @return Composition
     */
    public function getComposition()
    {
        return $this->composition;
    }

    /**
     * @param Composition $composition
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return RolesCompos
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
}
