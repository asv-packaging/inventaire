<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide')]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Ecran::class, cascade: ['persist'])]
    private $ecrans;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: PcFixe::class, cascade: ['persist'])]
    private $pcFixes;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: PcPortable::class, cascade: ['persist'])]
    private $pcPortables;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Tablette::class, cascade: ['persist'])]
    private $tablettes;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: TelephonePortable::class, cascade: ['persist'])]
    private $telephonePortables;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: TelephoneFixe::class, cascade: ['persist'])]
    private $telephoneFixes;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Utilisateur::class, cascade: ['persist'])]
    private $utilisateurs;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Serveur::class, cascade: ['persist'])]
    private $serveurs;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Imprimante::class, cascade: ['persist'])]
    private $imprimantes;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Onduleur::class, cascade: ['persist'])]
    private $onduleurs;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
