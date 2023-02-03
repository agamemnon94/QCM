<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassesRepository::class)]
class Classes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Eleves::class, mappedBy: 'eleve_classe')]
    private Collection $eleves;

    #[ORM\ManyToMany(targetEntity: Questionnaires::class, mappedBy: 'questionnaire_classe')]
    private Collection $questionnaires;

    #[ORM\Column]
    private ?bool $active = null;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->questionnaires = new ArrayCollection();
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
     * @return Collection<int, Eleves>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addEleves(Eleves $eleves): self
    {
        if (!$this->eleves->contains($eleves)) {
            $this->eleves->add($eleves);
            $eleves->addEleveClasse($this);
        }

        return $this;
    }

    public function removeEleves(Eleves $eleves): self
    {
        if ($this->eleves->removeElement($eleves)) {
            $eleves->removeEleveClasse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Questionnaires>
     */
    public function getQuestionnaires(): Collection
    {
        return $this->questionnaires;
    }

    public function addQuestionnaire(Questionnaires $questionnaire): self
    {
        if (!$this->questionnaires->contains($questionnaire)) {
            $this->questionnaires->add($questionnaire);
            $questionnaire->addQuestionnaireClasse($this);
        }

        return $this;
    }

    public function removeQuestionnaire(Questionnaires $questionnaire): self
    {
        if ($this->questionnaires->removeElement($questionnaire)) {
            $questionnaire->removeQuestionnaireClasse($this);
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
