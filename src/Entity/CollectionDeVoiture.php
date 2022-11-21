<?php

namespace App\Entity;

use App\Repository\CollectionDeVoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * @ORM\Entity(repositoryClass=CollectionDeVoitureRepository::class)
 */
class CollectionDeVoiture
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
     * @ORM\OneToMany(targetEntity=Voiture::class, mappedBy="collectionDeVoiture")
     */
    private $Voitures;

    /**
     * @ORM\ManyToOne(targetEntity=Membre::class, inversedBy="collections")
     */
    private $membre;

    public function __construct()
    {
        $this->Voitures = new ArrayCollection();
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

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->Voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->Voitures->contains($voiture)) {
            $this->Voitures[] = $voiture;
            $voiture->setCollectionDeVoiture($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->Voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getCollectionDeVoiture() === $this) {
                $voiture->setCollectionDeVoiture(null);
            }
        }
        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }


    public function __toString() 
    {
        $s = $this->getDescription() .' - '. 'dans la collection de :'. '' . $this->getMembre()->__toString();
        return $s;
    }
    
}
