<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Ecran::class, cascade: ['persist'])]
    private Collection $ecrans;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: TelephonePortable::class, cascade: ['persist'])]
    private Collection $telephonePortables;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Tablette::class, cascade: ['persist'])]
    private Collection $tablettes;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: PcFixe::class, cascade: ['persist'])]
    private Collection $pcFixes;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: PcPortable::class, cascade: ['persist'])]
    private Collection $pcPortables;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(name: 'entreprise_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Entreprise $entreprise = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: TelephoneFixe::class)]
    private Collection $telephoneFixes;

    public function __construct()
    {
        $this->ecrans = new ArrayCollection();
        $this->telephonePortables = new ArrayCollection();
        $this->tablettes = new ArrayCollection();
        $this->pcFixes = new ArrayCollection();
        $this->pcPortables = new ArrayCollection();
        $this->telephoneFixes = new ArrayCollection();
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Ecran>
     */
    public function getEcrans(): Collection
    {
        return $this->ecrans;
    }

    public function addEcran(Ecran $ecran): static
    {
        if (!$this->ecrans->contains($ecran)) {
            $this->ecrans->add($ecran);
            $ecran->setUtilisateur($this);
        }

        return $this;
    }

    public function removeEcran(Ecran $ecran): static
    {
        if ($this->ecrans->removeElement($ecran)) {
            // set the owning side to null (unless already changed)
            if ($ecran->getUtilisateur() === $this) {
                $ecran->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TelephonePortable>
     */
    public function getTelephonePortables(): Collection
    {
        return $this->telephonePortables;
    }

    public function addTelephonePortable(TelephonePortable $telephonePortable): static
    {
        if (!$this->telephonePortables->contains($telephonePortable)) {
            $this->telephonePortables->add($telephonePortable);
            $telephonePortable->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTelephonePortable(TelephonePortable $telephonePortable): static
    {
        if ($this->telephonePortables->removeElement($telephonePortable)) {
            // set the owning side to null (unless already changed)
            if ($telephonePortable->getUtilisateur() === $this) {
                $telephonePortable->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tablette>
     */
    public function getTablettes(): Collection
    {
        return $this->tablettes;
    }

    public function addTablette(Tablette $tablette): static
    {
        if (!$this->tablettes->contains($tablette)) {
            $this->tablettes->add($tablette);
            $tablette->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTablette(Tablette $tablette): static
    {
        if ($this->tablettes->removeElement($tablette)) {
            // set the owning side to null (unless already changed)
            if ($tablette->getUtilisateur() === $this) {
                $tablette->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PcFixe>
     */
    public function getPcFixes(): Collection
    {
        return $this->pcFixes;
    }

    public function addPcFix(PcFixe $pcFix): static
    {
        if (!$this->pcFixes->contains($pcFix)) {
            $this->pcFixes->add($pcFix);
            $pcFix->setUtilisateur($this);
        }

        return $this;
    }

    public function removePcFix(PcFixe $pcFix): static
    {
        if ($this->pcFixes->removeElement($pcFix)) {
            // set the owning side to null (unless already changed)
            if ($pcFix->getUtilisateur() === $this) {
                $pcFix->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PcPortable>
     */
    public function getPcPortables(): Collection
    {
        return $this->pcPortables;
    }

    public function addPcPortable(PcPortable $pcPortable): static
    {
        if (!$this->pcPortables->contains($pcPortable)) {
            $this->pcPortables->add($pcPortable);
            $pcPortable->setUtilisateur($this);
        }

        return $this;
    }

    public function removePcPortable(PcPortable $pcPortable): static
    {
        if ($this->pcPortables->removeElement($pcPortable)) {
            // set the owning side to null (unless already changed)
            if ($pcPortable->getUtilisateur() === $this) {
                $pcPortable->setUtilisateur(null);
            }
        }

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

    /**
     * @return Collection<int, TelephoneFixe>
     */
    public function getTelephoneFixes(): Collection
    {
        return $this->telephoneFixes;
    }

    public function addTelephoneFix(TelephoneFixe $telephoneFix): static
    {
        if (!$this->telephoneFixes->contains($telephoneFix)) {
            $this->telephoneFixes->add($telephoneFix);
            $telephoneFix->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTelephoneFix(TelephoneFixe $telephoneFix): static
    {
        if ($this->telephoneFixes->removeElement($telephoneFix)) {
            // set the owning side to null (unless already changed)
            if ($telephoneFix->getUtilisateur() === $this) {
                $telephoneFix->setUtilisateur(null);
            }
        }

        return $this;
    }
}
