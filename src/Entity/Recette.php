<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=RecetteRepository::class)
 */
class Recette
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getRecette"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getRecette"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"getRecette"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=category::class, inversedBy="recettes", cascade={"persist"})
     * @Groups({"getRecette"})
     */
    private $categoryPlat;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="recettes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getRecette"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=user::class, inversedBy="favoriesRecette")
     */
    private $favories;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="recette", orphanRemoval=true,  cascade={"persist"})
     * @Groups({"getRecette"})
     */
    private $images;

    public function __construct()
    {
        $this->favories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCategoryPlat(): ?Category
    {
        return $this->categoryPlat;
    }

    public function setCategoryPlat(?Category $categoryPlat): self
    {
        if (null !== $categoryPlat && !$categoryPlat->getRecettes()->contains($this)) {
            $categoryPlat->addRecette($this);
        }

        $this->categoryPlat = $categoryPlat;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getFavories(): Collection
    {
        return $this->favories;
    }

    public function addFavory(user $favory): self
    {
        if (!$this->favories->contains($favory)) {
            $this->favories[] = $favory;
        }

        return $this;
    }

    public function removeFavory(user $favory): self
    {
        $this->favories->removeElement($favory);

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRecette($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRecette() === $this) {
                $image->setRecette(null);
            }
        }

        return $this;
    }

    
}
