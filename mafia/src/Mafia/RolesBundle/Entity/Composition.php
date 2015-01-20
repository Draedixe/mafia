<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Composition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\CompositionRepository")
 */
class Composition
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
     * @var string
     *
     * @ORM\Column(name="nomCompo", type="string", length=255)
     */
    private $nomCompo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="officielle", type="boolean")
     */
    private $officielle;


    /**
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\OptionRole", cascade={"persist"})
     * @ORM\JoinTable(name="options_roles_compo")
     */
    private $optionsRoles;

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
     * Set nomCompo
     *
     * @param string $nomCompo
     * @return Composition
     */
    public function setNomCompo($nomCompo)
    {
        $this->nomCompo = $nomCompo;

        return $this;
    }

    /**
     * Get nomCompo
     *
     * @return string 
     */
    public function getNomCompo()
    {
        return $this->nomCompo;
    }

    /**
     * Set officielle
     *
     * @param boolean $officielle
     * @return Composition
     */
    public function setOfficielle($officielle)
    {
        $this->officielle = $officielle;

        return $this;
    }

    /**
     * Get officielle
     *
     * @return boolean 
     */
    public function getOfficielle()
    {
        return $this->officielle;
    }

    public function __construct()
    {
        $this->rolesCompo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->optionsRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param OptionRole $option
     */
    public function addOptionRole(OptionRole $option)
    {
        $this->optionsRoles[] = $option;
    }

    /**
     * @param OptionRole $option
     */
    public function removeOptionRole(OptionRole $option)
    {
        $this->optionsRoles->removeElement($option);
    }

    /**
     * Get optionsRoles
     *
     * @return ArrayCollection
     */
    public function getOptionsRoles()
    {
        return $this->optionsRoles;
    }

}
