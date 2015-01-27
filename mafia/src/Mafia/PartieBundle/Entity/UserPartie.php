<?php

namespace Mafia\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPartie
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserPartie
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
     * @ORM\Column(name="lastWord", type="string", length=255)
     */
    private $lastWord;

    /**
     * @var string
     *
     * @ORM\Column(name="deathNote", type="string", length=255)
     */
    private $deathNote;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vivant", type="boolean")
     */
    private $vivant;

    /**
     * @var integer
     *
     * @ORM\Column(name="bloquePar", type="integer")
     */
    private $bloquePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="protegePar", type="integer")
     */
    private $protegePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="essencePar", type="integer")
     */
    private $essencePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="controlePar", type="integer")
     */
    private $controlePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="sauvePar", type="integer")
     */
    private $sauvePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="echangePar", type="integer")
     */
    private $echangePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="emprisonnePar", type="integer")
     */
    private $emprisonnePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="piegePar", type="integer")
     */
    private $piegePar;

    /**
     * @var integer
     *
     * @ORM\Column(name="capaciteRestante", type="integer")
     */
    private $capaciteRestante;

    /**
     * @var integer
     *
     * @ORM\Column(name="tempsEntreCapacite", type="integer")
     */
    private $tempsEntreCapacite;

    /**
     * @var dateTime
     *
     * @ORM\Column(name="derniereActivite", type="datetime")
     */
    private $derniereActivite;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\RolesBundle\Entity\Role", cascade={"persist"})
     */
    private $role;


    /**
     * @ORM\ManyToOne(targetEntity="Mafia\PartieBundle\Entity\Partie", inversedBy="UserPartie")
     * @ORM\JoinColumn(nullable=false)
     */

    private $partie;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
     * Set lastWord
     *
     * @param string $lastWord
     * @return UserPartie
     */
    public function setLastWord($lastWord)
    {
        $this->lastWord = $lastWord;

        return $this;
    }

    /**
     * Get lastWord
     *
     * @return string 
     */
    public function getLastWord()
    {
        return $this->lastWord;
    }

    /**
     * Set deathNote
     *
     * @param string $deathNote
     * @return UserPartie
     */
    public function setDeathNote($deathNote)
    {
        $this->deathNote = $deathNote;

        return $this;
    }

    /**
     * Get deathNote
     *
     * @return string 
     */
    public function getDeathNote()
    {
        return $this->deathNote;
    }

    /**
     * Set vivant
     *
     * @param boolean $vivant
     * @return UserPartie
     */
    public function setVivant($vivant)
    {
        $this->vivant = $vivant;

        return $this;
    }

    /**
     * Get vivant
     *
     * @return boolean 
     */
    public function getVivant()
    {
        return $this->vivant;
    }

    /**
     * Set bloquePar
     *
     * @param integer $bloquePar
     * @return UserPartie
     */
    public function setBloquePar($bloquePar)
    {
        $this->bloquePar = $bloquePar;

        return $this;
    }

    /**
     * Get bloquePar
     *
     * @return integer 
     */
    public function getBloquePar()
    {
        return $this->bloquePar;
    }

    /**
     * Set protegePar
     *
     * @param integer $protegePar
     * @return UserPartie
     */
    public function setProtegePar($protegePar)
    {
        $this->protegePar = $protegePar;

        return $this;
    }

    /**
     * Get protegePar
     *
     * @return integer 
     */
    public function getProtegePar()
    {
        return $this->protegePar;
    }

    /**
     * Set essencePar
     *
     * @param integer $essencePar
     * @return UserPartie
     */
    public function setEssencePar($essencePar)
    {
        $this->essencePar = $essencePar;

        return $this;
    }

    /**
     * Get essencePar
     *
     * @return integer 
     */
    public function getEssencePar()
    {
        return $this->essencePar;
    }

    /**
     * Set controlePar
     *
     * @param integer $controlePar
     * @return UserPartie
     */
    public function setControlePar($controlePar)
    {
        $this->controlePar = $controlePar;

        return $this;
    }

    /**
     * Get controlePar
     *
     * @return integer 
     */
    public function getControlePar()
    {
        return $this->controlePar;
    }

    /**
     * Set sauvePar
     *
     * @param integer $sauvePar
     * @return UserPartie
     */
    public function setSauvePar($sauvePar)
    {
        $this->sauvePar = $sauvePar;

        return $this;
    }

    /**
     * Get sauvePar
     *
     * @return integer 
     */
    public function getSauvePar()
    {
        return $this->sauvePar;
    }

    /**
     * Set echangePar
     *
     * @param integer $echangePar
     * @return UserPartie
     */
    public function setEchangePar($echangePar)
    {
        $this->echangePar = $echangePar;

        return $this;
    }

    /**
     * Get echangePar
     *
     * @return integer 
     */
    public function getEchangePar()
    {
        return $this->echangePar;
    }

    /**
     * Set emprisonnePar
     *
     * @param integer $emprisonnePar
     * @return UserPartie
     */
    public function setEmprisonnePar($emprisonnePar)
    {
        $this->emprisonnePar = $emprisonnePar;

        return $this;
    }

    /**
     * Get emprisonnePar
     *
     * @return integer 
     */
    public function getEmprisonnePar()
    {
        return $this->emprisonnePar;
    }

    /**
     * Set piegePar
     *
     * @param integer $piegePar
     * @return UserPartie
     */
    public function setPiegePar($piegePar)
    {
        $this->piegePar = $piegePar;

        return $this;
    }

    /**
     * Get piegePar
     *
     * @return integer 
     */
    public function getPiegePar()
    {
        return $this->piegePar;
    }

    /**
     * Set capaciteRestante
     *
     * @param integer $capaciteRestante
     * @return UserPartie
     */
    public function setCapaciteRestante($capaciteRestante)
    {
        $this->capaciteRestante = $capaciteRestante;

        return $this;
    }

    /**
     * Get capaciteRestante
     *
     * @return integer
     */
    public function getCapaciteRestante()
    {
        return $this->capaciteRestante;
    }

    /**
     * Set tempsEntreCapacite
     *
     * @param integer $tempsEntreCapacite
     * @return UserPartie
     */
    public function setTempsEntreCapacite($tempsEntreCapacite)
    {
        $this->tempsEntreCapacite = $tempsEntreCapacite;

        return $this;
    }

    /**
     * Get tempsEntreCapacite
     *
     * @return integer 
     */
    public function getTempsEntreCapacite()
    {
        return $this->tempsEntreCapacite;
    }

    /**
     * @return dateTime
     */
    public function getDerniereActivite()
    {
        return $this->derniereActivite;
    }

    /**
     * @param dateTime $derniereActivite
     */
    public function setDerniereActivite($derniereActivite)
    {
        $this->derniereActivite = $derniereActivite;
    }


}
