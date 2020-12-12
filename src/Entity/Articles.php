<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 *  @Vich\Uploadable
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="articles")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @var \DateTime $created_at
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
    */
    private $created_at;

    /**
     * @var \DateTime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

// Près de la propriété $featured_image
    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $featured_image;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="articles", orphanRemoval=true)
     */
    private $commentaires;


    /**
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="featured_image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categories", inversedBy="articles")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MotsCles", inversedBy="articles")
     */
    private $mots_cles;



    // Dans les Getters/setters
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updated_at = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getFeaturedImage()
    {
        return $this->featured_image;
    }

    public function setFeaturedImage($featured_image)
    {
        $this->featured_image = $featured_image;

        return $this;
    }

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->mots_cles = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    // public function setCreatedAt(?\DateTimeInterface $created_at): self
    // {
    //     $this->created_at = $created_at;

    //     return $this;
    // }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    // public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    // {
    //     $this->updated_at = $updated_at;

    //     return $this;
    // }



    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setArticles($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticles() === $this) {
                $commentaire->setArticles(null);
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
        return $this->titre;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function wordLimiter($str, $limit = 120, $endstr = '…'){
        $str = strip_tags($str); 
        if(strlen($str) <= $limit) return $str;
            
        $out = substr($str, 0, $limit);
        $pos = strrpos($out, " ");
        if ($pos>0) {
            $out = substr($out, 0, $pos);
        }
        return $out .= $endstr;
    }


    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): ?self
    {

            
            if (!$this->categories->contains($category)) {
                $this->categories[] = $category;
                // $category->addArticle($this);
            }
        return $this;
    }

    // public function addCategory(Categories $category): self
    // {
    //     if (!$this->categories->contains($category)) {
    //         array_push($this->categories, $category);   
    //         // $this->categories[] = $category;
    //     }

    //     return $this;
    // }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|MotsCles[]
     */
    public function getMotsCles(): Collection
    {
        return $this->mots_cles;
    }

    public function addMotsCle(MotsCles $motsCle): self
    {
        if (!$this->mots_cles->contains($motsCle)) {
            $this->mots_cles[] = $motsCle;
        }

        return $this;
    }

    public function removeMotsCle(MotsCles $motsCle): self
    {
        if ($this->mots_cles->contains($motsCle)) {
            $this->mots_cles->removeElement($motsCle);
        }

        return $this;
    }

}
