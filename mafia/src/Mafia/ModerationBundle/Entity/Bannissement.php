<?php

namespace Mafia\ModerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mafia\UserBundle\Entity\User;

/**
 * Bannissement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\ModerationBundle\Entity\BannissementRepository")
 */
class Bannissement
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
     * @var \DateTime
     *
     * @ORM\Column(name="debutBannissement", type="datetime")
     */
    private $debutBannissement;

    /**
     * @var string
     *
     * @ORM\Column(name="explication", type="text")
     */
    private $explication;

    /**
     * @var integer
     *
     * @ORM\Column(name="tempsBannissement", type="integer")
     */
    private $tempsBannissement;

    /**
     * @var string
     *
     * @ORM\Column(name="unite", type="string", length=1)
     */
    private $unite;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="userBanni",nullable=false)
     */
    private $userBanni;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="userBannant",nullable=false)
     */
    private $modoBannant;

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
     * Set debutBannissement
     *
     * @param \DateTime $debutBannissement
     * @return Bannissement
     */
    public function setDebutBannissement($debutBannissement)
    {
        $this->debutBannissement = $debutBannissement;

        return $this;
    }

    /**
     * Get debutBannissement
     *
     * @return \DateTime 
     */
    public function getDebutBannissement()
    {
        return $this->debutBannissement;
    }

    /**
     * Set explication
     *
     * @param string $explication
     * @return Bannissement
     */
    public function setExplication($explication)
    {
        $this->explication = $explication;

        return $this;
    }

    /**
     * Get explication
     *
     * @return string 
     */
    public function getExplication()
    {
        return $this->explication;
    }

    /**
     * Set tempsBannissement
     *
     * @param integer $tempsBannissement
     * @return Bannissement
     */
    public function setTempsBannissement($tempsBannissement)
    {
        $this->tempsBannissement = $tempsBannissement;

        return $this;
    }

    /**
     * Get tempsBannissement
     *
     * @return integer 
     */
    public function getTempsBannissement()
    {
        return $this->tempsBannissement;
    }

    /**
     * @return User
     */
    public function getModoBannant()
    {
        return $this->modoBannant;
    }

    /**
     * @param User $modoBannant
     */
    public function setModoBannant($modoBannant)
    {
        $this->modoBannant = $modoBannant;
    }

    /**
     * @return User
     */
    public function getUserBanni()
    {
        return $this->userBanni;
    }

    /**
     * @param User $userBanni
     */
    public function setUserBanni($userBanni)
    {
        $this->userBanni = $userBanni;
    }

    /**
     * @return string
     */
    public function getUnite()
    {
        return $this->unite;
    }

    /**
     * @param string $unite
     */
    public function setUnite($unite)
    {
        $this->unite = $unite;
    }


}
