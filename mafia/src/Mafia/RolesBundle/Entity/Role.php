<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Mafia\RolesBundle\Entity\Crime;
use Mafia\RolesBundle\Entity\Categorie;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\RoleRepository")
 */
class Role
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
     * @ORM\Column(name="nomRole", type="string", length=100)
     */
    private $nomRole;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="enum_role", type="integer")
     */
    private $enum_role;

    /**
     * @var integer
     *
     * @ORM\Column(name="enum_faction", type="integer")
     */
    private $enum_faction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="roleUnique", type="boolean")
     */
    private $roleUnique;


    /**
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\Categorie", cascade={"persist"})
     * @ORM\JoinTable(name="categories_role")
     */
    private $categoriesRole;

    /**
     * @ORM\ManyToMany(targetEntity="Mafia\RolesBundle\Entity\Crime", cascade={"persist"})
     * @ORM\JoinTable(name="crimes_role")
     */
    private $crimesRole;


    public function __construct()
    {
        $this->categoriesRole = new \Doctrine\Common\Collections\ArrayCollection();
        $this->crimesRole = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nomRole
     *
     * @param string $nom
     * @return Role
     */
    public function setNomRole($nom)
    {
        $this->nomRole = $nom;

        return $this;
    }

    /**
     * Get nomRole
     *
     * @return string 
     */
    public function getNomRole()
    {
        return $this->nomRole;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get enum_faction
     *
     * @return int
     */
    public function getEnum_faction()
    {
        return $this->enum_faction;
    }

    /**
     * Set enum_faction
     *
     * @param int $enum_faction
     */
    public function setEnum_faction($enum_faction)
    {
        $this->enum_faction = $enum_faction;
    }

    /**
     * Get enum_role
     *
     * @return int
     */
    public function getEnum_role()
    {
        return $this->enum_role;
    }

    /**
     * Set enum_role
     *
     * @param int $enum_role
     */
    public function setEnum_role($enum_role)
    {
        $this->enum_role = $enum_role;
    }

    /**
     * Get roleUnique
     *
     * @return boolean
     */
    public function isUnique()
    {
        return $this->roleUnique;
    }

    /**
     * Set roleUnique
     *
     * @param boolean $unique
     */
    public function setUnique($unique)
    {
        $this->roleUnique = $unique;
    }

    /**
     * @param Categorie $categorie
     */
    public function addCategorieRole(Categorie $categorie)
    {
        $this->categoriesRole[] = $categorie;
    }

    /**
     * @param Categorie $categorie
     */
    public function removeCategorieRole(Categorie $categorie)
    {
        $this->categoriesRole->removeElement($categorie);
    }

    /**
     * Get categoriesRole
     *
     * @return ArrayCollection
     */
    public function getCategoriesRole()
    {
        return $this->categoriesRole;
    }

    /**
     * @param Crime $crime
     */
    public function addCrimeRole(Crime $crime)
    {
        $this->crimesRole[] = $crime;
    }

    /**
     * @param Crime $crime
     */
    public function removeCrimeRole(Crime $crime)
    {
        $this->crimesRole->removeElement($crime);
    }

    /**
     * Get crimesRole
     *
     * @return ArrayCollection
     */
    public function getCrimesRole()
    {
        return $this->crimesRole;
    }
}