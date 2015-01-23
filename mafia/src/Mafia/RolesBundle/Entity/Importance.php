<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Importance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\ImportanceRepository")
 */
class Importance
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
     * @ORM\Column(name="valeur", type="integer")
     */
    private $valeur;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set valeur
     *
     * @param integer $valeur
     * @return Importance
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return integer 
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
