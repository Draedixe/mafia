<?php

namespace Mafia\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Partie
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
     * @ORM\Column(name="nomPartie", type="string", length=255)
     */
    private $nomPartie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tempsEnCours", type="datetime")
     */
    private $tempsEnCours;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\PartieBundle\Entity\UserPartie", mappedBy="Partie")
     */
    private $userPartie;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\PartieBundle\Entity\Parametres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paramètres;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Composition")
     * @ORM\JoinColumn(nullable=false)
     */
    private $composition;

    /**
     * @return mixed
     */
    public function getUserPartie()
    {
        return $this->userPartie;
    }

    /**
     * @param mixed $userPartie
     */
    public function setUserPartie($userPartie)
    {
        $this->userPartie = $userPartie;
    }

    /**
     * @return mixed
     */
    public function getParamètres()
    {
        return $this->paramètres;
    }

    /**
     * @param mixed $paramètres
     */
    public function setParamètres($paramètres)
    {
        $this->paramètres = $paramètres;
    }

    /**
     * @return mixed
     */
    public function getComposition()
    {
        return $this->composition;
    }

    /**
     * @param mixed $composition
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;
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
     * Set nomPartie
     *
     * @param string $nomPartie
     * @return Partie
     */
    public function setNomPartie($nomPartie)
    {
        $this->nomPartie = $nomPartie;

        return $this;
    }

    /**
     * Get nomPartie
     *
     * @return string 
     */
    public function getNomPartie()
    {
        return $this->nomPartie;
    }

    /**
     * Set tempsEnCours
     *
     * @param \DateTime $tempsEnCours
     * @return Partie
     */
    public function setTempsEnCours($tempsEnCours)
    {
        $this->tempsEnCours = $tempsEnCours;

        return $this;
    }

    /**
     * Get tempsEnCours
     *
     * @return \DateTime 
     */
    public function getTempsEnCours()
    {
        return $this->tempsEnCours;
    }
}
