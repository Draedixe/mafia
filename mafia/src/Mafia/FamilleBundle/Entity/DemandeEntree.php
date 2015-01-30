<?php

namespace Mafia\FamilleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mafia\UserBundle\Entity\User;

/**
 * DemandeEntree
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mafia\FamilleBundle\Entity\DemandeEntreeRepository")
 */
class DemandeEntree
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
     * @ORM\Column(name="messageDemande", type="string", length=255)
     */
    private $messageDemande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="date")
     */
    private $dateDemande;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\UserBundle\Entity\User")
     */
    private $demandeur;

    /**
     * @ORM\ManyToOne(targetEntity="Mafia\FamilleBundle\Entity\Famille")
     */
    private $familleDemandee;

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
     * Set messageDemande
     *
     * @param string $messageDemande
     * @return DemandeEntree
     */
    public function setMessageDemande($messageDemande)
    {
        $this->messageDemande = $messageDemande;

        return $this;
    }

    /**
     * Get messageDemande
     *
     * @return string 
     */
    public function getMessageDemande()
    {
        return $this->messageDemande;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     * @return DemandeEntree
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime 
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * @return User
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }

    /**
     * @param User $demandeur
     */
    public function setDemandeur($demandeur)
    {
        $this->demandeur = $demandeur;
    }

    /**
     * @return Famille
     */
    public function getFamilleDemandee()
    {
        return $this->familleDemandee;
    }

    /**
     * @param Famille $familleDemandee
     */
    public function setFamilleDemandee($familleDemandee)
    {
        $this->familleDemandee = $familleDemandee;
    }


}
