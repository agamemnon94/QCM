<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[Assert\Regex(
    //     pattern:'',
    //     match:true,
    //     message:''
    // )]
    #[ORM\Column(length: 45)]
    private ?string $type = null;

    // #[Assert\Regex(
    //     pattern:'',
    //     match:true,
    //     message:''
    // )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $img = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToMany(targetEntity: Questionnaires::class, mappedBy: 'questionnaire_question')]
    private Collection $questionnaires;

    #[ORM\ManyToOne(inversedBy: 'category_question')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categories = null;

    #[ORM\OneToMany(mappedBy: 'questions', targetEntity: Reponses::class)]
    private Collection $question_reponse;

    #[ORM\OneToMany(mappedBy: 'questions', targetEntity: ReponsesEleves::class)]
    private Collection $reponsesEleves;

    public function __construct()
    {
        $this->questionnaires = new ArrayCollection();
        $this->question_reponse = new ArrayCollection();
        $this->reponsesEleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

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
            $questionnaire->addQuestionnaireQuestion($this);
        }

        return $this;
    }

    public function removeQuestionnaire(Questionnaires $questionnaire): self
    {
        if ($this->questionnaires->removeElement($questionnaire)) {
            $questionnaire->removeQuestionnaireQuestion($this);
        }

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Reponses>
     */
    public function getQuestionReponse(): Collection
    {
        return $this->question_reponse;
    }

    public function addQuestionReponse(Reponses $questionReponse): self
    {
        if (!$this->question_reponse->contains($questionReponse)) {
            $this->question_reponse->add($questionReponse);
            $questionReponse->setQuestions($this);
        }

        return $this;
    }

    public function removeQuestionReponse(Reponses $questionReponse): self
    {
        if ($this->question_reponse->removeElement($questionReponse)) {
            // set the owning side to null (unless already changed)
            if ($questionReponse->getQuestions() === $this) {
                $questionReponse->setQuestions(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReponsesEleves>
     */
    public function getReponsesEleves(): Collection
    {
        return $this->reponsesEleves;
    }
}
