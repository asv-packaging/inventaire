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

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Ecran::class)]
    private Collection $ecrans;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Telephone::class)]
    private Collection $telephones;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Tablette::class)]
    private Collection $tablettes;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: PcFixe::class)]
    private Collection $pcFixes;

    public function __construct()
    {
        $this->ecrans = new ArrayCollection();
        $this->telephones = new ArrayCollection();
        $this->tablettes = new ArrayCollection();
        $this->pcFixes = new ArrayCollection();
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Ecran>
     */
    public function getEcrans(): Collection
    {
        return $this->ecrans;
    }

    public function addEcran(Ecran $ecran): self
    {
        if (!$this->ecrans->contains($ecran)) {
            $this->ecrans->add($ecran);
            $ecran->setUtilisateur($this);
        }

        return $this;
    }

    public function removeEcran(Ecran $ecran): self
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
     * @return Collection<int, Telephone>
     */
    public function getTelephones(): Collection
    {
        return $this->telephones;
    }

    public function addTelephone(Telephone $telephone): self
    {
        if (!$this->telephones->contains($telephone)) {
            $this->telephones->add($telephone);
            $telephone->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTelephone(Telephone $telephone): self
    {
        if ($this->telephones->removeElement($telephone)) {
            // set the owning side to null (unless already changed)
            if ($telephone->getUtilisateur() === $this) {
                $telephone->setUtilisateur(null);
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

    public function addTablette(Tablette $tablette): self
    {
        if (!$this->tablettes->contains($tablette)) {
            $this->tablettes->add($tablette);
            $tablette->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTablette(Tablette $tablette): self
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

    public function addPcFix(PcFixe $pcFix): self
    {
        if (!$this->pcFixes->contains($pcFix)) {
            $this->pcFixes->add($pcFix);
            $pcFix->setUtilisateur($this);
        }

        return $this;
    }

    public function removePcFix(PcFixe $pcFix): self
    {
        if ($this->pcFixes->removeElement($pcFix)) {
            // set the owning side to null (unless already changed)
            if ($pcFix->getUtilisateur() === $this) {
                $pcFix->setUtilisateur(null);
            }
        }

        return $this;
    }
}
