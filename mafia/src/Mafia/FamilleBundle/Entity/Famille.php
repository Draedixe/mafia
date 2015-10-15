<?php

namespace Mafia\FamilleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mafia\PartieBundle\Entity\Chat;
use Mafia\UserBundle\Entity\User;

/**
 * Famille
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Famille
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\PartieBundle\Entity\Chat")
     */
    private $chat;

    /**
     * @ORM\OneToOne(targetEntity="Mafia\UserBundle\Entity\User")
     */
    private $chef;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\FamilleBundle\Entity\DemandeEntree",mappedBy="familleDemandee")
     */
    private $demandes;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\FamilleBundle\Entity\Proposition",mappedBy="familleProposante")
     */
    private $propositions;

    /**
     * @ORM\OneToMany(targetEntity="Mafia\UserBundle\Entity\User",mappedBy="famille")
     */
    private $membres;

    /**
     * @param User $membre
     */
    public function addMembre(User $membre)
    {
        $this->membres[] = $membre;
    }

    /**
     * @param User $membre
     */
    public function removeMembre(User $membre)
    {
        $this->membres->removeElement($membre);
    }

    /**
     * Get membres
     *
     * @return ArrayCollection
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * @param Proposition $proposition
     */
    public function addProposition(Proposition $proposition)
    {
        $this->propositions[] = $proposition;
    }

    /**
     * @param Proposition $proposition
     */
    public function removeProposition(Proposition $proposition)
    {
        $this->propositions->removeElement($proposition);
    }

    /**
     * Get propositions
     *
     * @return ArrayCollection
     */
    public function getPropositions()
    {
        return $this->propositions;
    }

    /**
     * @param DemandeEntree $demande
     */
    public function addDemande(DemandeEntree $demande)
    {
        $this->demandes[] = $demande;
    }

    /**
     * @param DemandeEntree $demande
     */
    public function removeDemande(DemandeEntree $demande)
    {
        $this->demandes->removeElement($demande);
    }

    /**
     * Get demandes
     *
     * @return ArrayCollection
     */
    public function getDemandes()
    {
        return $this->demandes;
    }

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->propositions = new ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Famille
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Famille
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     */
    public function setChat($chat)
    {
        $this->chat = $chat;
    }

    /**
     * @return User
     */
    public function getChef()
    {
        return $this->chef;
    }

    /**
     * @param User $chef
     */
    public function setChef($chef)
    {
        $this->chef = $chef;
    }
}
