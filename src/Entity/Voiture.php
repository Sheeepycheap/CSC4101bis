<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Foundation\Testing\DatabaseMigrations;
/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Description de la tâche
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity=CollectionDeVoiture::class, inversedBy="Voitures")
     */
    private $collectionDeVoiture;

    /**
     * @ORM\ManyToMany(targetEntity=Moteur::class, inversedBy="voitures")
     */
    private $Moteur;

    /**
     * @ORM\ManyToMany(targetEntity=Galerie::class, mappedBy="Voiture")
     */
    private $galeries;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->Moteur = new ArrayCollection();
        $this->galeries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getCollectionDeVoiture(): ?CollectionDeVoiture
    {
        return $this->collectionDeVoiture;
    }

    public function setCollectionDeVoiture(?CollectionDeVoiture $collectionDeVoiture): self
    {
        $this->collectionDeVoiture = $collectionDeVoiture;

        return $this;
    }

    public function __toString() 
    {
        
        $s = $this->getId() .' - '. $this->getMarque() . ' - ' . $this->getModele() ;
        $s .= ' - ' . $this->getCouleur() . ' - :' . $this->getDescription();
        return $s;
    }

    /**
     * @return Collection<int, Moteur>
     */
    public function getMoteur(): Collection
    {
        return $this->Moteur;
    }

    public function addMoteur(Moteur $moteur): self
    {
        if (!$this->Moteur->contains($moteur)) {
            $this->Moteur[] = $moteur;
        }

        return $this;
    }

    public function removeMoteur(Moteur $moteur): self
    {
        $this->Moteur->removeElement($moteur);

        return $this;
    }

    public function setMoteur(?Moteur $moteur): self
    {
        $this->Moteur = $moteur;

        return $this;
    }

    /**
     * @return Collection<int, Galerie>
     */
    public function getGaleries(): Collection
    {
        return $this->galeries;
    }

    public function addGalery(Galerie $galery): self
    {
        if (!$this->galeries->contains($galery)) {
            $this->galeries[] = $galery;
            $galery->addVoiture($this);
        }

        return $this;
    }

    public function removeGalery(Galerie $galery): self
    {
        if ($this->galeries->removeElement($galery)) {
            $galery->removeVoiture($this);
        }

        return $this;
    }
}
