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
    private ?Etat $etat = null;

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

    #[ORM\ManyToOne(inversedBy: 'serveurs')]
    #[ORM\JoinColumn(name: 'stockage_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Stockage $stockage = null;

    #[ORM\Column]
    private ?bool $physique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_achat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_garantie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'serveurs')]
    #[ORM\JoinColumn(name: 'fournisseur_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'serveurs')]
    #[ORM\JoinColumn(name: 'systeme_exploitation_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?SystemeExploitation $systeme_exploitation = null;

    #[ORM\ManyToOne(inversedBy: 'serveurs')]
    #[ORM\JoinColumn(name: 'entreprise_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_contrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stockage_type = null;

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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->numero_serie;
    }

    public function setNumeroSerie(?string $numero_serie): static
    {
        $this->numero_serie = $numero_serie;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getProcesseur(): ?string
    {
        return $this->processeur;
    }

    public function setProcesseur(?string $processeur): static
    {
        $this->processeur = $processeur;

        return $this;
    }

    public function getMemoire(): ?int
    {
        return $this->memoire;
    }

    public function setMemoire(?int $memoire): static
    {
        $this->memoire = $memoire;

        return $this;
    }

    public function getStockageNombre(): ?int
    {
        return $this->stockage_nombre;
    }

    public function setStockageNombre(?int $stockage_nombre): static
    {
        $this->stockage_nombre = $stockage_nombre;

        return $this;
    }

    public function getStockage(): ?Stockage
    {
        return $this->stockage;
    }

    public function setStockage(?Stockage $stockage): static
    {
        $this->stockage = $stockage;

        return $this;
    }

    public function isPhysique(): ?bool
    {
        return $this->physique;
    }

    public function setPhysique(bool $physique): static
    {
        $this->physique = $physique;

        return $this;
    }

    public function getDateAchat(): ?string
    {
        return $this->date_achat;
    }

    public function setDateAchat(?string $date_achat): static
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getDateGarantie(): ?string
    {
        return $this->date_garantie;
    }

    public function setDateGarantie(?string $date_garantie): static
    {
        $this->date_garantie = $date_garantie;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getSystemeExploitation(): ?SystemeExploitation
    {
        return $this->systeme_exploitation;
    }

    public function setSystemeExploitation(?SystemeExploitation $systeme_exploitation): static
    {
        $this->systeme_exploitation = $systeme_exploitation;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getDateContrat(): ?string
    {
        return $this->date_contrat;
    }

    public function setDateContrat(?string $date_contrat): static
    {
        $this->date_contrat = $date_contrat;

        return $this;
    }

    public function getStockageType(): ?string
    {
        return $this->stockage_type;
    }

    public function setStockageType(?string $stockage_type): static
    {
        $this->stockage_type = $stockage_type;

        return $this;
    }
}
