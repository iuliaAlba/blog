<?php

namespace App\Entity;

use App\Repository\CategoriesArticlesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesArticlesRepository::class)
 */
class CategoriesArticles
{


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="categoriesArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="categoriesArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $articles;


    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getArticles(): ?Articles
    {
        return $this->articles;
    }

    public function setArticles(?Articles $articles): self
    {
        $this->articles = $articles;

        return $this;
    }
    
}
