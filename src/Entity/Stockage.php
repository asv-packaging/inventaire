<?php

namespace App\Entity;

use App\Repository\StockageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockageRepository::class)]
class Stockage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'stockage', targetEntity: PcPortable::class, cascade: ['persist'])]
    private $pcPortables;

    #[ORM\OneToMany(mappedBy: 'stockage', targetEntity: PcFixe::class, cascade: ['persist'])]
    private $pcFixes;

    #[ORM\OneToMany(mappedBy: 'stockage', targetEntity: Serveur::class, cascade: ['persist'])]
    private $serveurs;

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
