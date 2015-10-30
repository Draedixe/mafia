<?php

namespace Mafia\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mafia\UserBundle\Entity\User;

/**
 * MessagePrive
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\MessageBundle\Entity\MessagePriveRepository")
 */
class MessagePrive
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
     * @ORM\Column(name="texte", type="string", length=255)
     */
    private $texte;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vu", type="boolean")
     */
    private $vu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoi", type="datetime")
     */
    private $dateEnvoi;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediteur;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recepteur;

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
     * Set texte
     *
     * @param string $texte
     * @return MessagePrive
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set vu
     *
     * @param boolean $vu
     * @return MessagePrive
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu
     *
     * @return boolean 
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return MessagePrive
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime 
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Get expediteur
     *
     * @return User
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * @param User $expediteur
     */
    public function setExpediteur(User $expediteur)
    {
        $this->expediteur = $expediteur;
    }

    /**
     * @return User
     */
    public function getRecepteur()
    {
        return $this->recepteur;
    }

    /**
     * @param User $recepteur
     */
    public function setRecepteur(User $recepteur)
    {
        $this->recepteur = $recepteur;
    }


}
