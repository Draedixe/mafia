<?php

namespace Mafia\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mafia\FamilleBundle\Entity\Famille;
use Mafia\FamilleBundle\Entity\Proposition;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\FamilleBundle\Entity\Famille")
     */
    private $famille;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\FamilleBundle\Entity\Proposition",mappedBy="userPropose")
     */
    private $propositions;


    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\UserPartie")
     */
    private $userCourant;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbMessagesNonLus", type="integer")
     */
    private $nbMessagesNonLus;

    /**
     * @return mixed
     */
    public function getUserCourant()
    {
        return $this->userCourant;
    }

    /**
     * @param mixed $userCourant
     */
    public function setUserCourant($userCourant)
    {
        $this->userCourant = $userCourant;
    }



    /**
     * @param Proposition $proposition
     */
    public function addProposition(Proposition $proposition)
    {
        $this->propositions[] = $proposition;
    }

    /**
     * @param Proposition $proposition
     */
    public function removeProposition(Proposition $proposition)
    {
        $this->propositions->removeElement($proposition);
    }

    /**
     * Get propositions
     *
     * @return ArrayCollection
     */
    public function getPropositions()
    {
        return $this->propositions;
    }

    public function __construct()
    {
        parent::__construct();
        $this->propositions = new ArrayCollection();
        $this->points = 0;
        $this->nbMessagesNonLus = 0;
    }

    /**
     * @return Famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * @param Famille $famille
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
    }




    /**a
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getNbMessagesNonLus()
    {
        return $this->nbMessagesNonLus;
    }

    public function ajoutMessageNonLu()
    {
        $this->nbMessagesNonLus++;
    }

    /**
     * @param int $nbMessagesNonLus
     */
    public function setNbMessagesNonLus($nbMessagesNonLus)
    {
        $this->nbMessagesNonLus = $nbMessagesNonLus;
    }


}
