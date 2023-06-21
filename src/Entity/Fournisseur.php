<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Ecran::class, cascade: ['persist'])]
    private $ecrans;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: PcFixe::class, cascade: ['persist'])]
    private $pcFixes;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: PcPortable::class, cascade: ['persist'])]
    private $pcPortables;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Tablette::class, cascade: ['persist'])]
    private $tablettes;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: TelephonePortable::class, cascade: ['persist'])]
    private $telephonePortables;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: TelephoneFixe::class, cascade: ['persist'])]
    private $telephoneFixes;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Serveur::class, cascade: ['persist'])]
    private $serveurs;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Imprimante::class, cascade: ['persist'])]
    private $imprimantes;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Onduleur::class, cascade: ['persist'])]
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
