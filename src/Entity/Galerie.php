<?php

namespace App\Entity;

use App\Repository\GalerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalerieRepository::class)
 */
class Galerie
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
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="galeries")
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=Voiture::class, inversedBy="galeries")
     */
    private $Voiture;

    public function __construct()
    {
        $this->Voiture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getCreator(): ?Membre
    {
        return $this->creator;
    }

    public function setCreator(?Membre $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoiture(): Collection
    {
        return $this->Voiture;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->Voiture->contains($voiture)) {
            $this->Voiture[] = $voiture;
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        $this->Voiture->removeElement($voiture);

        return $this;
    }

    public function __toString() 
    {
        $s = 'Creator :' . $this->getCreator();
        return $s;
    }
}
