<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $matriculeFiscale = null;

    #[ORM\Column(length: 255)]
    private ?string $secteur = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $siteweb = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\OneToOne(inversedBy: 'company', cascade: ['persist', 'remove'])]
    private ?User $responsable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LogoUrl = null;


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

    public function getMatriculeFiscale(): ?string
    {
        return $this->matriculeFiscale;
    }

    public function setMatriculeFiscale(string $matriculeFiscale): static
    {
        $this->matriculeFiscale = $matriculeFiscale;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(string $siteweb): static
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    public function setResponsable(?User $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->LogoUrl;
    }

    public function setLogoUrl(?string $LogoUrl): static
    {
        $this->LogoUrl = $LogoUrl;

        return $this;
    }
}
