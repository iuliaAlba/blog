<?php

namespace App\Entity;

use App\Repository\MotsClesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MotsClesRepository::class)
 */
class MotsCles
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
    private $mot_cle;

    /**
     * @Gedmo\Slug(fields={"mot_cle"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=MotsClesArticles::class, mappedBy="mots_cles", orphanRemoval=true)
     */
    private $motsClesArticles;

    public function __construct()
    {
        $this->motsClesArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotCle(): ?string
    {
        return $this->mot_cle;
    }

    public function setMotCle(string $mot_cle): self
    {
        $this->mot_cle = $mot_cle;

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
     * @return Collection|MotsClesArticles[]
     */
    public function getMotsClesArticles(): Collection
    {
        return $this->motsClesArticles;
    }

    public function addMotsClesArticle(MotsClesArticles $motsClesArticle): self
    {
        if (!$this->motsClesArticles->contains($motsClesArticle)) {
            $this->motsClesArticles[] = $motsClesArticle;
            $motsClesArticle->setMotsCles($this);
        }

        return $this;
    }

    public function removeMotsClesArticle(MotsClesArticles $motsClesArticle): self
    {
        if ($this->motsClesArticles->removeElement($motsClesArticle)) {
            // set the owning side to null (unless already changed)
            if ($motsClesArticle->getMotsCles() === $this) {
                $motsClesArticle->setMotsCles(null);
            }
        }

        return $this;
    }
    /**
     * Generates the magic method
     * 
     */
    public function __toString(){
        // to show the name of the Category in the select
        return $this->mot_cle;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
