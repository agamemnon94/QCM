<?php

namespace App\Entity;

use App\Repository\ReponsesElevesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponsesElevesRepository::class)]
class ReponsesEleves
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column(nullable: true)]
    private ?int $reponses = null;

    #[ORM\Column]
    private ?bool $success = null;

    #[ORM\ManyToOne(inversedBy: 'reponsesEleves')]
    private ?Examens $examens = null;

    #[ORM\ManyToOne(inversedBy: 'reponsesEleves')]
    private ?Questions $questions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function getReponses(): ?int
    {
        return $this->reponses;
    }

    public function setReponses(?int $reponses): self
    {
        $this->reponses = $reponses;

        return $this;
    }

    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getExamens(): ?Examens
    {
        return $this->examens;
    }

    public function setExamens(?Examens $examens): self
    {
        $this->examens = $examens;

        return $this;
    }

    public function getQuestions(): ?Questions
    {
        return $this->questions;
    }

    public function setQuestions(?Questions $questions): self
    {
        $this->questions = $questions;

        return $this;
    }
}
