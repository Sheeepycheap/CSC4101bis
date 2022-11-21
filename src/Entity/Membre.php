<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 */
class Membre
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CollectionDeVoiture::class, mappedBy="membre")
     */
    private $collections;

    /**
     * @ORM\OneToMany(targetEntity=Galerie::class, mappedBy="creator")
     */
    private $galeries;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="Membre", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->collections = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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


    public function setCollection(CollectionDeVoiture $collection): self
    {
        $this->collections = $collection;

        return $this;
    }

    /**
     * @return Collection<int, CollectionDeVoiture>
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    public function addCollection(CollectionDeVoiture $collection): self
    {
        if (!$this->collections->contains($collection)) {
            $this->collections[] = $collection;
            $collection->setMembre($this);
        }

        return $this;
    }

    public function removeCollection(CollectionDeVoiture $collection): self
    {
        if ($this->collections->removeElement($collection)) {
            // set the owning side to null (unless already changed)
            if ($collection->getMembre() === $this) {
                $collection->setMembre(null);
            }
        }
        return $this;
    }


    public function __toString() 
    {
        $s =  $this->getName();;
        return $s;
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
            $galery->setCreator($this);
        }

        return $this;
    }

    public function removeGalery(Galerie $galery): self
    {
        if ($this->galeries->removeElement($galery)) {
            // set the owning side to null (unless already changed)
            if ($galery->getCreator() === $this) {
                $galery->setCreator(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setMembre(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getMembre() !== $this) {
            $user->setMembre($this);
        }

        $this->user = $user;

        return $this;
    }
}
