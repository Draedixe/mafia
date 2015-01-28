<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\Importance", cascade={"persist"})
     * @ORM\JoinTable(name="importances_roles_compo")
     */
    private $importances;

    /**
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\OptionRole", cascade={"persist"})
     * @ORM\JoinTable(name="options_roles_compo")
     */
    private $optionsRoles;

    /**
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\RolesCompos", cascade={"persist"})
     * @ORM\JoinTable(name="roles_compo")
     */
    private $rolesCompo;

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
        $this->rolesCompo = new ArrayCollection();
        $this->optionsRoles = new ArrayCollection();
        $this->importances = new ArrayCollection();
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

    /**
     * @param Importance $importance
     */
    public function addImportance(Importance $importance)
    {
        $this->importances[] = $importance;
    }

    /**
     * @param Importance $importance
     */
    public function removeImportance(Importance $importance)
    {
        $this->importances->removeElement($importance);
    }

    /**
     * Get importances
     *
     * @return ArrayCollection
     */
    public function getImportances()
    {
        return $this->importances;
    }

    /**
     * @param RolesCompos $roleCompo
     */
    public function addRoleCompo(RolesCompos $roleCompo)
    {
        $this->rolesCompo[] = $roleCompo;
    }

    /**
     * @param RolesCompos $roleCompo
     */
    public function removeRoleCompo(RolesCompos $roleCompo)
    {
        $this->rolesCompo->removeElement($roleCompo);
    }

    /**
     * Get rolesCompo
     *
     * @return ArrayCollection
     */
    public function getRolesCompo()
    {
        return $this->rolesCompo;
    }
}
