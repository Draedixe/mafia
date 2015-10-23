<?php

namespace Mafia\ModerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="descriptionRequete", type="string", length=255)
     */
    private $descriptionRequete;


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
}
