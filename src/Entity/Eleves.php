<?php

namespace App\Entity;

use App\Repository\ElevesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: 'email',
    message: 'Cet adresse email est déjà utilisée.'
)]
#[ORM\Entity(repositoryClass: ElevesRepository::class)]
class Eleves
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(
        pattern: '/^[A-Za-z][A-Za-z -][^\_].+[A-Za-z]$/',
        match: true,
        message: 'Votre prénom doit commencer par une lettre et ne doit contenir que des lettres, des tirets ou des espaces.'
    )]
    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[Assert\Regex(
        pattern: '/^[A-Za-z][A-Za-z -].*[A-Za-z]$/',
        match: true,
        message: 'Votre nom doit commencer par une lettre et ne doit contenir que des lettres, des tirets ou des espaces.'
    )]
    #[ORM\Column(length: 50)]
    private ?string $lastname = null;


    #[Assert\Regex(
        pattern: '/[-0-9a-zA-Z.+_]{2,35}+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}$/',
        match: true,
        message: 'Votre adresse email doit contenir au moins 2 caratères, un signe @, un nom de serveur et un nom de domaine. ex. : paul@serveur.fr'
    )]
    #[Assert\Email(
        message: 'L\'email {{ value }} n\'est pas valid.',
    )]
    #[ORM\Column(length: 50, nullable: true, unique: true)]
    private ?string $email = null;

    // #[ORM\ManyToMany(targetEntity: Questionnaires::class, inversedBy: 'eleves')]
    // private Collection $examen;

    #[ORM\ManyToMany(targetEntity: Classes::class, inversedBy: 'eleves')]
    private Collection $eleve_classe;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Examens::class)]
    private Collection $examens;

    public function __construct()
    {
        $this->eleve_classe = new ArrayCollection();
        $this->questionnaire = new ArrayCollection();
        $this->examens = new ArrayCollection();
        $this->testExamens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Classes>
     */
    public function getEleveClasse(): Collection
    {
        return $this->eleve_classe;
    }

    public function addEleveClasse(Classes $eleveClasse): self
    {
        if (!$this->eleve_classe->contains($eleveClasse)) {
            $this->eleve_classe->add($eleveClasse);
        }

        return $this;
    }

    public function removeEleveClasse(Classes $eleveClasse): self
    {
        $this->eleve_classe->removeElement($eleveClasse);

        return $this;
    }

    /**
     * @return Collection<int, Examens>
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamens(Examens $examens): self
    {
        if (!$this->examens->contains($examens)) {
            $this->examens->add($examens);
            $examens->setEleve($this);
        }

        return $this;
    }

    public function removeExamens(Examens $examens): self
    {
        if ($this->examens->removeElement($examens)) {
            // set the owning side to null (unless already changed)
            if ($examens->getEleve() === $this) {
                $examens->setEleve(null);
            }
        }

        return $this;
    }
}
