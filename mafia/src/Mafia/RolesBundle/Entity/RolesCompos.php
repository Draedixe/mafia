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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Role")
     */
    private $role;

    /**
     * @var integer
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}
