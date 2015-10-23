<?php

namespace Mafia\ModerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Denonciation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Denonciation
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
     * @ORM\Column(name="descriptionDenonciation", type="text")
     */
    private $descriptionDenonciation;

    /**
     * @var string
     *
     * @ORM\Column(name="titreDenonciation", type="string", length=255)
     */
    private $titreDenonciation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="traite", type="boolean")
     */
    private $traite;


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
     * Set descriptionDenonciation
     *
     * @param string $descriptionDenonciation
     * @return Denonciation
     */
    public function setDescriptionDenonciation($descriptionDenonciation)
    {
        $this->descriptionDenonciation = $descriptionDenonciation;

        return $this;
    }

    /**
     * Get descriptionDenonciation
     *
     * @return string 
     */
    public function getDescriptionDenonciation()
    {
        return $this->descriptionDenonciation;
    }

    /**
     * Set titreDenonciation
     *
     * @param string $titreDenonciation
     * @return Denonciation
     */
    public function setTitreDenonciation($titreDenonciation)
    {
        $this->titreDenonciation = $titreDenonciation;

        return $this;
    }

    /**
     * Get titreDenonciation
     *
     * @return string 
     */
    public function getTitreDenonciation()
    {
        return $this->titreDenonciation;
    }

    /**
     * Set traite
     *
     * @param boolean $traite
     * @return Denonciation
     */
    public function setTraite($traite)
    {
        $this->traite = $traite;

        return $this;
    }

    /**
     * Get traite
     *
     * @return boolean 
     */
    public function getTraite()
    {
        return $this->traite;
    }
}
