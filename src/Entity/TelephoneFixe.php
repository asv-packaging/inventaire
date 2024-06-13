<?php

namespace App\Entity;

use App\Repository\TelephoneFixeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TelephoneFixeRepository::class)]
class TelephoneFixe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ligne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip = null;

    #[ORM\ManyToOne(inversedBy: 'telephoneFixes')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'telephoneFixes')]
    #[ORM\JoinColumn(name: 'fournisseur_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'telephoneFixes')]
    #[ORM\JoinColumn(name: 'emplacement_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Emplacement $emplacement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Le type ne doit pas être vide')]
    private ?string $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'L\'état ne doit pas être vide')]
    private ?Etat $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_serie = null;

    #[ORM\ManyToOne(inversedBy: 'telephoneFixes')]
    #[ORM\JoinColumn(name: 'entreprise_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_installation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_achat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_garantie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne(): ?string
    {
        return $this->ligne;
    }

    public function setLigne(?string $ligne): static
    {
        $this->ligne = $ligne;

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

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

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getDateInstallation(): ?string
    {
        return $this->date_installation;
    }

    public function setDateInstallation(?string $date_installation): static
    {
        $this->date_installation = $date_installation;

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
