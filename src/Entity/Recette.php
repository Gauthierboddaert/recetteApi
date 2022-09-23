<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use App\Entity\Category;
use App\Entity\User;
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
     * @ORM\ManyToOne(targetEntity=category::class, inversedBy="recettes")
     * @Groups({"getRecette"})
     */
    private $categoryPlat;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="recettes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"getRecette"})
     */
    private $user;

    

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

    public function getCategoryPlat(): ?category
    {
        return $this->categoryPlat;
    }

    public function setCategoryPlat(?category $categoryPlat): self
    {
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

    
}
