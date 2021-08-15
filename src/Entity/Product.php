<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du produit est obligatoire")
     * @Assert\Length(min=3, max=255, minMessage="Le nom du produit doit faire plus de 3 caractères")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotBlank(message="Le prix du produit est obligatoire")
     * @Assert\Type(type="integer", message="Cette valeur doit un être un {{ type }}")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message="L'adresse doit être une url")
     * @Assert\NotBlank(message="L'url de l'image du produit est obligatoire")
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description du produit est obligatoire")
     * @Assert\Length(min=10, minMessage="La description courte doit faire au moins 10 caractères")
     */
    private $shortDescription;

    // public static function loadValidatorMetadata(ClassMetadata $metaData)
    // {
    //     $metaData->addPropertyConstraints('name', [
    //         new Assert\NotBlank(['message' => "Le nom du produit est obligatoire"]),
    //         new Assert\Length([
    //             'min' => 3,
    //             'max' => 255,
    //             'minMessage' => "Le nom du produidoit comporter au moins trois caractères"
    //         ])
    //     ]);
    //     $metaData->addPropertyConstraint('price', new Assert\NotBlank(['message' => "Le prix du produit ne peut pas être vide."]));
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
