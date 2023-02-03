<?php

namespace App\Entity;

use App\Repository\QuestionnairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionnairesRepository::class)]
#[UniqueEntity(
    fields: 'form_code',
    message: 'Ce code de formulaire existe déjà'
)]
class Questionnaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15, unique: true)]
    private ?string $form_code = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $consigne = null;

    #[ORM\ManyToMany(targetEntity: Classes::class, inversedBy: 'questionnaires')]
    private Collection $questionnaire_classe;

    #[ORM\ManyToMany(targetEntity: Questions::class, inversedBy: 'questionnaires')]
    private Collection $questionnaire_question;

    #[ORM\OneToMany(mappedBy: 'questionnaire', targetEntity: Examens::class)]
    private Collection $examens;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->questionnaire_classe = new ArrayCollection();
        $this->questionnaire_question = new ArrayCollection();
        $this->examens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormCode(): ?string
    {
        return $this->form_code;
    }

    public function setFormCode(string $form_code): self
    {
        $this->form_code = $form_code;

        return $this;
    }

    public function getConsigne(): ?string
    {
        return $this->consigne;
    }

    public function setConsigne(string $consigne): self
    {
        $this->consigne = $consigne;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getQuestionnaireClasse(): Collection
    {
        return $this->questionnaire_classe;
    }

    public function addQuestionnaireClasse(Classes $questionnaireClasse): self
    {
        if (!$this->questionnaire_classe->contains($questionnaireClasse)) {
            $this->questionnaire_classe->add($questionnaireClasse);
        }

        return $this;
    }

    public function removeQuestionnaireClasse(Classes $questionnaireClasse): self
    {
        $this->questionnaire_classe->removeElement($questionnaireClasse);

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getQuestionnaireQuestion(): Collection
    {
        return $this->questionnaire_question;
    }

    public function addQuestionnaireQuestion(Questions $questionnaireQuestion): self
    {
        if (!$this->questionnaire_question->contains($questionnaireQuestion)) {
            $this->questionnaire_question->add($questionnaireQuestion);
        }

        return $this;
    }

    public function removeQuestionnaireQuestion(Questions $questionnaireQuestion): self
    {
        $this->questionnaire_question->removeElement($questionnaireQuestion);

        return $this;
    }

    /**
     * @return Collection<int, Examens>
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamen(Examens $examen): self
    {
        if (!$this->examens->contains($examen)) {
            $this->examens->add($examen);
            $examen->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeExamen(Examens $examen): self
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getQuestionnaire() === $this) {
                $examen->setQuestionnaire(null);
            }
        }

        return $this;
    }
}
