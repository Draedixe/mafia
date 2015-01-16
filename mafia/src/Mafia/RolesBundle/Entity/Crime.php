<?php

namespace Mafia\RolesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crime
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\RolesBundle\Entity\CrimeRepository")
 */
class Crime
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
     * @ORM\Column(name="nomCrime", type="string", length=50)
     */
    private $nomCrime;


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
     * Set nomCrime
     *
     * @param string $nomCrime
     * @return Crime
     */
    public function setNomCrime($nomCrime)
    {
        $this->nomCrime = $nomCrime;

        return $this;
    }

    /**
     * Get nomCrime
     *
     * @return string 
     */
    public function getNomCrime()
    {
        return $this->nomCrime;
    }

    public function __toString()
    {
        return $this->nomCrime;
    }
}
