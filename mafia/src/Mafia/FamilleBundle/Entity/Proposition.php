<?php

namespace Mafia\FamilleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proposition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\FamilleBundle\Entity\PropositionRepository")
 */
class Proposition
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
     * @ORM\Column(name="messageProposition", type="text")
     */
    private $messageProposition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateProposition", type="date")
     */
    private $dateProposition;


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
     * Set messageProposition
     *
     * @param string $messageProposition
     * @return Proposition
     */
    public function setMessageProposition($messageProposition)
    {
        $this->messageProposition = $messageProposition;

        return $this;
    }

    /**
     * Get messageProposition
     *
     * @return string 
     */
    public function getMessageProposition()
    {
        return $this->messageProposition;
    }

    /**
     * Set dateProposition
     *
     * @param \DateTime $dateProposition
     * @return Proposition
     */
    public function setDateProposition($dateProposition)
    {
        $this->dateProposition = $dateProposition;

        return $this;
    }

    /**
     * Get dateProposition
     *
     * @return \DateTime 
     */
    public function getDateProposition()
    {
        return $this->dateProposition;
    }
}
