<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OptionRole
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\OptionRoleRepository")
 */
class OptionRole
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
     * @var integer
     *
     * @ORM\Column(name="enumOption", type="integer")
     */
    private $enumOption;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Role")
     */
    private $role;

    /**
     * @var integer
     *
     * @ORM\Column(name="valeur", type="integer")
     */
    private $valeur;


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
     * Set enumOption
     *
     * @param integer $enumOption
     * @return OptionRole
     */
    public function setEnumOption($enumOption)
    {
        $this->enumOption = $enumOption;

        return $this;
    }

    /**
     * Get enumOption
     *
     * @return integer 
     */
    public function getEnumOption()
    {
        return $this->enumOption;
    }

    /**
     * Set valeur
     *
     * @param integer $valeur
     * @return OptionRole
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


}
