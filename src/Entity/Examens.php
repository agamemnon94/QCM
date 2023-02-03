<?php

namespace App\Entity;

use App\Repository\ExamensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamensRepository::class)]
class Examens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'examens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Eleves $eleve = null;

    #[ORM\ManyToOne(inversedBy: 'examens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaires $questionnaire = null;

    #[ORM\OneToMany(mappedBy: 'examens', targetEntity: ReponsesEleves::class)]
    private Collection $reponsesEleves;

    public function __construct()
    {
        $this->reponsesEleves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEleve(): ?Eleves
    {
        return $this->eleve;
    }
    // Vérifier s'il c'est utilisé
    public function setEleve(?Eleves $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    // Ajouté à 14h30
    public function addEleve(?Eleves $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaires
    {
        return $this->questionnaire;
    }

    // Vérifier s'il c'est utilisé
    public function setQuestionnaire(?Questionnaires $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }


    public function addQuestionnaire(?Questionnaires $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * @return Collection<int, ReponsesEleves>
     */
    public function getReponsesEleves(): Collection
    {
        return $this->reponsesEleves;
    }

    // public function addReponsesEleve(ReponsesEleves $reponsesEleves): self
    // {
    //     if (!$this->reponsesEleves->contains($reponsesEleves)) {
    //         $this->reponsesEleves->add($reponsesEleves);
    //         $reponsesEleves->setExamens($this);
    //     }

    //     return $this;
    // }

    // public function removeReponsesEleve(ReponsesEleves $reponsesEleves): self
    // {
    //     if ($this->reponsesEleves->removeElement($reponsesEleves)) {
    //         // set the owning side to null (unless already changed)
    //         if ($reponsesEleves->getExamens() === $this) {
    //             $reponsesEleves->setExamens(null);
    //         }
    //     }

    //     return $this;
    // }

}
