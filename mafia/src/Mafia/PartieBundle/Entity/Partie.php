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
     * @var integer
     *
     * @ORM\Column(name="phaseEnCours", type="integer")
     */
    private $phaseEnCours;

    /**
     * @var integer
     *
     * @ORM\Column(name="dureePhase", type="float")
     */
    private $dureePhase;

    /**
     * @var integer
     *
     * @ORM\Column(name="tempsJourRestant", type="float")
     */
    private $tempsJourRestant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debutPhase", type="datetime")
     */
    private $debutPhase;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\PartieBundle\Entity\UserPartie", mappedBy="partie")
     */
    private $userPartie;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\PartieBundle\Entity\Parametres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parametres;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\RolesBundle\Entity\Composition")
     * @ORM\JoinColumn(nullable=false)
     */
    private $composition;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\Chat")
     */
    private $chat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="commencee", type="boolean")
     */
     private $commencee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="terminee", type="boolean")
     */
    private $terminee;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $traitementAubeEnCours;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
       private $maireAnnonce;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $nombreJoueursMax;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\UserPartie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createur;

    /**
     * @var string
     *
     * @ORM\Column(name="typePartie", type="string", length=255)
     */
    private $typePartie;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\UserPartie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $accuse;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $numJour;


    function __construct()
    {
        $this->nombreJoueursMax = 15;
        $this->phaseEnCours=PhaseJeuEnum::SELECTION_DU_NOM;
        $this->dureePhase=0.2;
        $this->tempsJourRestant=0;
        $this->traitementAubeEnCours = false;
        $this->numJour = 1;
    }

    /**
     * @return int
     */
    public function getNumJour()
    {
        return $this->numJour;
    }

    /**
     * @param int $numJour
     */
    public function setNumJour($numJour)
    {
        $this->numJour = $numJour;
    }

    /**
     * @return mixed
     */
    public function getAccuse()
    {
        return $this->accuse;
    }

    /**
     * @param mixed $accuse
     */
    public function setAccuse($accuse)
    {
        $this->accuse = $accuse;
    }



    /**
     * @return string
     */
    public function getTypePartie()
    {
        return $this->typePartie;
    }

    /**
     * @param string $typePartie
     */
    public function setTypePartie($typePartie)
    {
        $this->typePartie = $typePartie;
    }


    /**
     * @return mixed
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * @param mixed $createur
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;
    }



    /**
     * @return int
     */
    public function getNombreJoueursMax()
    {
        return $this->nombreJoueursMax;
    }

    /**
     * @param int $nombreJoueursMax
     */
    public function setNombreJoueursMax($nombreJoueursMax)
    {
        $this->nombreJoueursMax = $nombreJoueursMax;
    }



    /**
     * @return boolean
     */
    public function isMaireAnnonce()
    {
        return $this->maireAnnonce;
    }

    /**
     * @param boolean $maireAnnonce
     */
    public function setMaireAnnonce($maireAnnonce)
    {
        $this->maireAnnonce = $maireAnnonce;
    }



    /**
     * @return boolean
     */
    public function isCommencee()
    {
        return $this->commencee;
    }

    /**
     * @param boolean $commencee
     */
    public function setCommencee($commencee)
    {
        $this->commencee = $commencee;
    }

    /**
     * @return boolean
     */
    public function isTerminee()
    {
        return $this->terminee;
    }

    /**
     * @param boolean $terminee
     */
    public function setTerminee($terminee)
    {
        $this->terminee = $terminee;
    }



    /**
     * @return mixed
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param mixed $chat
     */
    public function setChat($chat)
    {
        $this->chat = $chat;
    }

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
    public function getParametres()
    {
        return $this->parametres;
    }

    /**
     * @param mixed $parametres
     */
    public function setParametres($parametres)
    {
        $this->parametres = $parametres;
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
     * Set debutPhase
     *
     * @param \DateTime $tempsEnCours
     * @return Partie
     */
    public function setDebutPhase($tempsEnCours)
    {
        $this->debutPhase = $tempsEnCours;

        return $this;
    }

    /**
     * Get debutPhase
     *
     * @return \DateTime 
     */
    public function getDebutPhase()
    {
        return $this->debutPhase;
    }

    /**
     * @return int
     */
    public function getPhaseEnCours()
    {
        return $this->phaseEnCours;
    }

    /**
     * @param int $phaseEnCours
     */
    public function setPhaseEnCours($phaseEnCours)
    {
        $this->phaseEnCours = $phaseEnCours;
    }

    /**
     * @return int
     */
    public function getDureePhase()
    {
        return $this->dureePhase;
    }

    /**
     * @param int $dureePhase
     */
    public function setDureePhase($dureePhase)
    {
        $this->dureePhase = $dureePhase;
    }

    /**
     * @return int
     */
    public function getTempsJourRestant()
    {
        return $this->tempsJourRestant;
    }

    /**
     * @param int $tempsJourRestant
     */
    public function setTempsJourRestant($tempsJourRestant)
    {
        $this->tempsJourRestant = $tempsJourRestant;
    }

    /**
     * @return boolean
     */
    public function isTraitementAubeEnCours()
    {
        return $this->traitementAubeEnCours;
    }

    /**
     * @param boolean $traitementAubeEnCours
     */
    public function setTraitementAubeEnCours($traitementAubeEnCours)
    {
        $this->traitementAubeEnCours = $traitementAubeEnCours;
    }


}
