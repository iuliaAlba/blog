<?php

namespace App\Entity;

use App\Repository\MotsClesArticlesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MotsClesArticlesRepository::class)
 */
class MotsClesArticles
{


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=MotsCles::class, inversedBy="motsClesArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mots_cles;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="motsClesArticles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $articles;



    public function getMotsCles(): ?MotsCles
    {
        return $this->mots_cles;
    }

    public function setMotsCles(?MotsCles $mots_cles): self
    {
        $this->mots_cles = $mots_cles;

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
         /**
     * Generates the magic method
     * 
     */
    public function __toString(){
        // to show the name of the Category in the select
        return $this->mots_cles;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
