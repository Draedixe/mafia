<?php

namespace Mafia\PartieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Statut
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
     * @var integer
     *
     * @ORM\Column(name="enumStatut", type="integer")
     */
    private $enumStatut;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\UserPartie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $victime;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\UserPartie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acteur;

    /**
     * @return mixed
     */
    public function getVictime()
    {
        return $this->victime;
    }

    /**
     * @param mixed $victime
     */
    public function setVictime($victime)
    {
        $this->victime = $victime;
    }

    /**
     * @return mixed
     */
    public function getActeur()
    {
        return $this->acteur;
    }

    /**
     * @param mixed $acteur
     */
    public function setActeur($acteur)
    {
        $this->acteur = $acteur;
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
     * Set enumStatut
     *
     * @param integer $enumStatut
     * @return Status
     */
    public function setEnumStatut($enumStatut)
    {
        $this->enumStatut = $enumStatut;

        return $this;
    }

    /**
     * Get enumStatut
     *
     * @return integer 
     */
    public function getEnumStatut()
    {
        return $this->enumStatut;
    }
}
