<?php

namespace App\Entity;

use App\Repository\MoteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoteurRepository::class)
 */
class Moteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Moteur::class, inversedBy="sousMoteur")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Moteur::class, mappedBy="parent")
     */
    private $sousMoteur;

    /**
     * @ORM\ManyToMany(targetEntity=Voiture::class, mappedBy="Moteur")
     */
    private $voitures;

    public function __construct()
    {
        $this->sousMoteur = new ArrayCollection();
        $this->voitures = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSousMoteur(): Collection
    {
        return $this->sousMoteur;
    }

    public function addSousMoteur(self $sousMoteur): self
    {
        if (!$this->sousMoteur->contains($sousMoteur)) {
            $this->sousMoteur[] = $sousMoteur;
            $sousMoteur->setParent($this);
        }

        return $this;
    }

    public function removeSousMoteur(self $sousMoteur): self
    {
        if ($this->sousMoteur->removeElement($sousMoteur)) {
            // set the owning side to null (unless already changed)
            if ($sousMoteur->getParent() === $this) {
                $sousMoteur->setParent(null);
            }
        }

        return $this;
    }

    public function setSousMoteur(self $sousMoteur): self
    {
        $this->sousMoteur = $sousMoteur;
        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures[] = $voiture;
            $voiture->addMoteur($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->voitures->removeElement($voiture)) {
            $voiture->removeMoteur($this);
        }

        return $this;
    }

    public function __toString() 
    {
        $s = $this->getLabel();
        $s .=  ' - ' . $this->getDescription();
        return $s;
    }
}