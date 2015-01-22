<?php

namespace Mafia\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Mafia\PartieBundle\Entity\TypeNuitEnum;
use Mafia\PartieBundle\Entity\TypeJugementEnum;
use Mafia\PartieBundle\Entity\DebutPartieEnum;
/**
 * Parametres
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Parametres
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
     * @ORM\Column(type="string", length=100))
     */
    private $nomParametres;

    /**
     * @var float
     *
     * @Assert\Range(min=0.5,max=5.0)
     * @ORM\Column(type="float")
     */
    private $dureeDuJour;

    /**
     * @var integer
     *
     * @Assert\Range(min=0,max=3)
     * @ORM\Column(type="integer")
     */
    private $enumTypeDeJugement;

    /**
     * @var float
     *
     * @Assert\Range(min=0.5,max=5.0)
     * @ORM\Column(type="float")
     */
    private $dureeDeLaNuit;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $dernieresVolontes;

    /**
     * @var float
     *
     * @Assert\Range(min=0.5,max=5.0)
     * @ORM\Column(type="float")
     */
    private $tempsDeDiscussion;

    /**
     * @var integer
     *
     * @Assert\Range(min=0,max=2)
     * @ORM\Column(type="integer")
     */
    private $debutDuJeu;

    /**
     * @var integer
     *
     * @Assert\Range(min=0,max=2)
     * @ORM\Column(type="integer")
     */
    private $typeDeNuit;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $messagePrives;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $phaseDiscussion;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $choisirNom;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $officiel;


    /*
    * Paramètres par défaut
    */
    function __construct()
    {
        $this->dureeDuJour = 1.8;
        $this->enumTypeDeJugement = TypeJugementEnum::MAJORITE;
        $this->dureeDeLaNuit = 0.7;
        $this->dernieresVolontes = true;
        $this->debutDuJeu = DebutPartieEnum::JOUR_SANS_LYNCHAGE;
        $this->tempsDeDiscussion = 0.8;
        $this->typeDeNuit = TypeNuitEnum::DESCRIPTION_MORTS;
        $this->messagePrives = true;
        $this->choisirNom = true;
        $this->phaseDiscussion = true;
        $this->officiel = false;
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
     * Set dureeDuJour
     *
     * @param float $dureeDuJour
     * @return Parametres
     */
    public function setDureeDuJour($dureeDuJour)
    {
        $this->dureeDuJour = $dureeDuJour;

        return $this;
    }

    /**
     * Get dureeDuJour
     *
     * @return float 
     */
    public function getDureeDuJour()
    {
        return $this->dureeDuJour;
    }

    /**
     * Set enumTypeDeJugement
     *
     * @param integer $enumTypeDeJugement
     * @return Parametres
     */
    public function setEnumTypeDeJugement($enumTypeDeJugement)
    {
        $this->enumTypeDeJugement = $enumTypeDeJugement;

        return $this;
    }

    /**
     * Get enumTypeDeJugement
     *
     * @return integer 
     */
    public function getEnumTypeDeJugement()
    {
        return $this->enumTypeDeJugement;
    }

    /**
     * Set dureeDeLaNuit
     *
     * @param float $dureeDeLaNuit
     * @return Parametres
     */
    public function setDureeDeLaNuit($dureeDeLaNuit)
    {
        $this->dureeDeLaNuit = $dureeDeLaNuit;

        return $this;
    }

    /**
     * Get dureeDeLaNuit
     *
     * @return float 
     */
    public function getDureeDeLaNuit()
    {
        return $this->dureeDeLaNuit;
    }

    /**
     * Set dernieresVolontes
     *
     * @param boolean $dernieresVolontes
     * @return Parametres
     */
    public function setDernieresVolontes($dernieresVolontes)
    {
        $this->dernieresVolontes = $dernieresVolontes;

        return $this;
    }

    /**
     * Get dernieresVolontes
     *
     * @return boolean 
     */
    public function getDernieresVolontes()
    {
        return $this->dernieresVolontes;
    }

    /**
     * Set tempsDeDiscussion
     *
     * @param float $tempsDeDiscussion
     * @return Parametres
     */
    public function setTempsDeDiscussion($tempsDeDiscussion)
    {
        $this->tempsDeDiscussion = $tempsDeDiscussion;

        return $this;
    }

    /**
     * Get tempsDeDiscussion
     *
     * @return float 
     */
    public function getTempsDeDiscussion()
    {
        return $this->tempsDeDiscussion;
    }

    /**
     * Set debutDuJeu
     *
     * @param integer $debutDuJeu
     * @return Parametres
     */
    public function setDebutDuJeu($debutDuJeu)
    {
        $this->debutDuJeu = $debutDuJeu;

        return $this;
    }

    /**
     * Get debutDuJeu
     *
     * @return integer 
     */
    public function getDebutDuJeu()
    {
        return $this->debutDuJeu;
    }

    /**
     * Set typeDeNuit
     *
     * @param integer $typeDeNuit
     * @return Parametres
     */
    public function setTypeDeNuit($typeDeNuit)
    {
        $this->typeDeNuit = $typeDeNuit;

        return $this;
    }

    /**
     * Get typeDeNuit
     *
     * @return integer 
     */
    public function getTypeDeNuit()
    {
        return $this->typeDeNuit;
    }

    /**
     * Set messagePrives
     *
     * @param boolean $messagePrives
     * @return Parametres
     */
    public function setMessagePrives($messagePrives)
    {
        $this->messagePrives = $messagePrives;

        return $this;
    }

    /**
     * Get messagePrives
     *
     * @return boolean 
     */
    public function getMessagePrives()
    {
        return $this->messagePrives;
    }

    /**
     * Set phaseDiscussion
     *
     * @param boolean $phaseDiscussion
     * @return Parametres
     */
    public function setPhaseDiscussion($phaseDiscussion)
    {
        $this->phaseDiscussion = $phaseDiscussion;

        return $this;
    }

    /**
     * Get phaseDiscussion
     *
     * @return boolean 
     */
    public function getPhaseDiscussion()
    {
        return $this->phaseDiscussion;
    }

    /**
     * Set choisirNom
     *
     * @param boolean $choisirNom
     * @return Parametres
     */
    public function setChoisirNom($choisirNom)
    {
        $this->choisirNom = $choisirNom;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getOfficiel()
    {
        return $this->officiel;
    }

    /**
     * @param boolean $officiel
     * @return Parametres
     */
    public function setOfficiel($officiel)
    {
        $this->officiel = $officiel;

        return $this;
    }

    /**
     * Get choisirNom
     *
     * @return boolean 
     */
    public function getChoisirNom()
    {
        return $this->choisirNom;
    }

    /**
     * @return string
     */
    public function getNomParametres()
    {
        return $this->nomParametres;
    }

    /**
     * @param string $nomParametres
     * @return Parametres
     */
    public function setNomParametres($nomParametres)
    {
        $this->nomParametres = $nomParametres;

        return $this;
    }


}
