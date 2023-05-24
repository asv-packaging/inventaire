<?php

namespace App\Entity;

use App\Repository\ServeurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServeurRepository::class)]
class Serveur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modele = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Emplacement $emplacement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_serie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $processeur = null;

    #[ORM\Column(nullable: true)]
    private ?int $memoire = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockage_nombre = null;

    #[ORM\ManyToOne]
    private ?Stockage $stockage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $os = null;

    #[ORM\Column]
    private ?bool $physique = null;

    #[ORM\Column(nullable: true)]
    private ?int $date_achat = null;

    #[ORM\Column(nullable: true)]
    private ?int $date_garantie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->numero_serie;
    }

    public function setNumeroSerie(?string $numero_serie): self
    {
        $this->numero_serie = $numero_serie;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getProcesseur(): ?string
    {
        return $this->processeur;
    }

    public function setProcesseur(?string $processeur): self
    {
        $this->processeur = $processeur;

        return $this;
    }

    public function getMemoire(): ?int
    {
        return $this->memoire;
    }

    public function setMemoire(?int $memoire): self
    {
        $this->memoire = $memoire;

        return $this;
    }

    public function getStockageNombre(): ?int
    {
        return $this->stockage_nombre;
    }

    public function setStockageNombre(?int $stockage_nombre): self
    {
        $this->stockage_nombre = $stockage_nombre;

        return $this;
    }

    public function getStockage(): ?Stockage
    {
        return $this->stockage;
    }

    public function setStockage(?Stockage $stockage): self
    {
        $this->stockage = $stockage;

        return $this;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(?string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function isPhysique(): ?bool
    {
        return $this->physique;
    }

    public function setPhysique(bool $physique): self
    {
        $this->physique = $physique;

        return $this;
    }

    public function getDateAchat(): ?int
    {
        return $this->date_achat;
    }

    public function setDateAchat(?int $date_achat): self
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getDateGarantie(): ?int
    {
        return $this->date_garantie;
    }

    public function setDateGarantie(?int $date_garantie): self
    {
        $this->date_garantie = $date_garantie;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
