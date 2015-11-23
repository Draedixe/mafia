<?php

namespace Mafia\ModerationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mafia\UserBundle\Entity\User;

/**
 * Surveillance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\ModerationBundle\Entity\SurveillanceRepository")
 */
class Surveillance
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
     * @ORM\Column(name="raison", type="text")
     */
    private $raison;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="datetime")
     */
    private $debut;

    /**
     * @var boolean
     *
     * @ORM\Column(name="termine", type="boolean")
     */
    private $termine;

    /**
     * @var integer
     *
     * @ORM\Column(name="priorite", type="integer")
     */
    private $priorite;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="createur",nullable=false)
     */
    private $createur;

    /**
     * @ORM\ManyToMany(targetEntity="Mafia\UserBundle\Entity\User", cascade={"persist"})
     */
    private $usersSurveilles;

    function __construct()
    {
        $this->termine = false;
        $this->debut = new \DateTime();
        $this->usersSurveilles = new ArrayCollection();
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
     * Set raison
     *
     * @param string $raison
     * @return Surveillance
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;

        return $this;
    }

    /**
     * Get raison
     *
     * @return string 
     */
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Surveillance
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Surveillance
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set termine
     *
     * @param boolean $termine
     * @return Surveillance
     */
    public function setTermine($termine)
    {
        $this->termine = $termine;

        return $this;
    }

    /**
     * Get termine
     *
     * @return boolean 
     */
    public function getTermine()
    {
        return $this->termine;
    }

    /**
     * Get priorite
     *
     * @return int
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * @param int $priorite
     */
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;
    }

    /**
     * Get usersSurveilles
     *
     * @return ArrayCollection
     */
    public function getUsersSurveilles()
    {
        return $this->usersSurveilles;
    }

    /**
     * @param User $userSurveille
     */
    public function removeMessage(User $userSurveille)
    {
        $this->usersSurveilles->removeElement($userSurveille);
    }

    /**
     * @param User $userSurveille
     */
    public function addUserSurveille(User $userSurveille)
    {
        $this->usersSurveilles[] = $userSurveille;
    }

    /**
     * @return User
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * @param User $createur
     */
    public function setCreateur(User $createur)
    {
        $this->createur = $createur;
    }


}
