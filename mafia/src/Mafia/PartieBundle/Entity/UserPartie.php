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
     * @ORM\Column(name="essencePar", type="integer")
     */
    private $essencePar;


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
     * @var \DateTime
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
     * @ORM\OneToOne(targetEntity="Mafia\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



    /**
     * @return mixed
     */
    public function getPartie()
    {
        return $this->partie;
    }

    /**
     * @param mixed $partie
     */
    public function setPartie($partie)
    {
        $this->partie = $partie;
    }



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
     * @return \DateTime
     */
    public function getDerniereActivite()
    {
        return $this->derniereActivite;
    }

    /**
     * @param \DateTime $derniereActivite
     */
    public function setDerniereActivite($derniereActivite)
    {
        $this->derniereActivite = $derniereActivite;
    }


}
