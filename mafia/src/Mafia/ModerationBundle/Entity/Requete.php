<?php

namespace Mafia\ModerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mafia\UserBundle\Entity\User;

/**
 * Requete
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\ModerationBundle\Entity\RequeteRepository")
 */
class Requete
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
     * @ORM\Column(name="titreRequete", type="string", length=255)
     */
    private $titreRequete;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionRequete", type="text")
     */
    private $descriptionRequete;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\ModerationBundle\Entity\Requete")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reponse;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estReponse", type="boolean")
     */
    private $estReponse;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $requeteur;

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
     * Set descriptionRequete
     *
     * @param string $descriptionRequete
     * @return Requete
     */
    public function setDescriptionRequete($descriptionRequete)
    {
        $this->descriptionRequete = $descriptionRequete;

        return $this;
    }

    /**
     * Get descriptionRequete
     *
     * @return string 
     */
    public function getDescriptionRequete()
    {
        return $this->descriptionRequete;
    }

    /**
     * @return Requete
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * @param Requete $reponse
     */
    public function setReponse(Requete $reponse)
    {
        $this->reponse = $reponse;
    }

    /**
     * Get titreRequete
     * @return string
     */
    public function getTitreRequete()
    {
        return $this->titreRequete;
    }

    /**
     * @param string $titreRequete
     */
    public function setTitreRequete($titreRequete)
    {
        $this->titreRequete = $titreRequete;
    }

    /**
     * Get requeteur
     * @return User
     */
    public function getRequeteur()
    {
        return $this->requeteur;
    }

    /**
     * @param User $requeteur
     */
    public function setRequeteur(User $requeteur)
    {
        $this->requeteur = $requeteur;
    }

    /**
     * @return boolean
     */
    public function isEstReponse()
    {
        return $this->estReponse;
    }

    /**
     * @param boolean $estReponse
     */
    public function setEstReponse($estReponse)
    {
        $this->estReponse = $estReponse;
    }


}
