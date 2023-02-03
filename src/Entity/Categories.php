<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
#[UniqueEntity(
    fields: 'name',
    message: 'Ce nom de catégorie {{ value }} est déjà utilisé'
)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\Regex(
        pattern: '/\<\>/',
        match: false,
        message: 'Le nom de catégorie ne doit pas contenir de "<" et ou de ">".'
    )]
    #[Assert\Length(
        min: 3,
        max: 32,
        minMessage: 'Le nom de la catégorie doit compter au moins {{ limit }} caractères',
        maxMessage: 'Le nom de la catégorie doit compter au plus {{ limit }} caractères',
    )]
    #[ORM\Column(length: 30, unique: true)]
    #[Assert\NotNull]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Questions::class, orphanRemoval: true)]
    private Collection $category_question;

    public function __construct()
    {
        $this->category_question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Questions>
     */
    public function getCategoryQuestion(): Collection
    {
        return $this->category_question;
    }

    public function addCategoryQuestion(Questions $categoryQuestion): self
    {
        if (!$this->category_question->contains($categoryQuestion)) {
            $this->category_question->add($categoryQuestion);
            $categoryQuestion->setCategories($this);
        }

        return $this;
    }

    public function removeCategoryQuestion(Questions $categoryQuestion): self
    {
        if ($this->category_question->removeElement($categoryQuestion)) {
            // set the owning side to null (unless already changed)
            if ($categoryQuestion->getCategories() === $this) {
                $categoryQuestion->setCategories(null);
            }
        }

        return $this;
    }
}
