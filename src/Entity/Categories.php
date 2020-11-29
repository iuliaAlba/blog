<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
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
    private $nom;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=CategoriesArticles::class, mappedBy="categories", orphanRemoval=true)
     */
    private $categoriesArticles;

    public function __construct()
    {
        $this->categoriesArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    // public function setSlug(string $slug): self
    // {
    //     $this->slug = $slug;

    //     return $this;
    // }

    /**
     * @return Collection|CategoriesArticles[]
     */
    public function getCategoriesArticles(): Collection
    {
        return $this->categoriesArticles;
    }

    public function addCategoriesArticle(CategoriesArticles $categoriesArticle): self
    {
        if (!$this->categoriesArticles->contains($categoriesArticle)) {
            $this->categoriesArticles[] = $categoriesArticle;
            $categoriesArticle->setCategories($this);
        }

        return $this;
    }

    public function removeCategoriesArticle(CategoriesArticles $categoriesArticle): self
    {
        if ($this->categoriesArticles->removeElement($categoriesArticle)) {
            // set the owning side to null (unless already changed)
            if ($categoriesArticle->getCategories() === $this) {
                $categoriesArticle->setCategories(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
    
}
