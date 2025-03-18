<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $matriculeFiscle = null;

    #[ORM\Column(length: 255)]
    private ?string $secteur = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteweb = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $statuValidation = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $responsable = null;

    /**
     * @var Collection<int, JobOffer>
     */
    #[ORM\OneToMany(targetEntity: JobOffer::class, mappedBy: 'entreprise')]
    private Collection $jobOffers;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'entreprise')]
    private Collection $reviews;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getMatriculeFiscle(): ?string
    {
        return $this->matriculeFiscle;
    }

    public function setMatriculeFiscle(string $matriculeFiscle): static
    {
        $this->matriculeFiscle = $matriculeFiscle;

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

    public function setSiteweb(?string $siteweb): static
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isStatuValidation(): ?bool
    {
        return $this->statuValidation;
    }

    public function setStatuValidation(bool $statuValidation): static
    {
        $this->statuValidation = $statuValidation;

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

    /**
     * @return Collection<int, JobOffer>
     */
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): static
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers->add($jobOffer);
            $jobOffer->setEntreprise($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): static
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getEntreprise() === $this) {
                $jobOffer->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setEntreprise($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getEntreprise() === $this) {
                $review->setEntreprise(null);
            }
        }

        return $this;
    }
}
