<?php

namespace App\Entity;

use App\Repository\TelephoneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TelephoneRepository::class)]
class Telephone
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

    #[ORM\ManyToOne(inversedBy: 'telephones')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Statut $statut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_serie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imei1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imei2 = null;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

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

    public function getImei1(): ?string
    {
        return $this->imei1;
    }

    public function setImei1(?string $imei1): self
    {
        $this->imei1 = $imei1;

        return $this;
    }

    public function getImei2(): ?string
    {
        return $this->imei2;
    }

    public function setImei2(?string $imei2): self
    {
        $this->imei2 = $imei2;

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
