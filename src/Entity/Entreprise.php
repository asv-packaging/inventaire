<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
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
}
