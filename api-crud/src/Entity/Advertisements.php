<?php

namespace App\Entity;

use App\Repository\AdvertisementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertisementsRepository::class)]
class Advertisements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameAd = null;

    #[ORM\ManyToOne(inversedBy: 'advertisements', targetEntity: 'Companies')]
    #[ORM\JoinColumn(nullable: false, name:"companies_id",referencedColumnName:"id")]
    private ?Companies $companies = null;

    #[ORM\Column(length: 255)]
    private ?string $dateOfPublication = null;

    #[ORM\ManyToMany(targetEntity: Applicants::class, mappedBy: 'advertisements')]
    private Collection $applicants;

    #[ORM\OneToMany(mappedBy: 'advertisements', targetEntity: Jobs::class)]
    private Collection $jobs;

    public function __construct()
    {
        $this->applicants = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    //#[ORM\Column(type: Types::DATE_MUTABLE)]
    //private ?\DateTimeInterface $dateOfPublication = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAd(): ?string
    {
        return $this->nameAd;
    }

    public function setNameAd(string $nameAd): self
    {
        $this->nameAd = $nameAd;

        return $this;
    }

    public function getCompanies(): ?Companies
    {
        return $this->companies;
    }

    public function setCompanies(?Companies $companies): self
    {
        $this->companies = $companies;

        return $this;
    }

    /*public function getDateOfPublication(): ?\DateTimeInterface
    {
        return $this->dateOfPublication;
    }

    public function setDateOfPublication(?\DateTimeInterface $dateOfPublication): self
    {
        $this->dateOfPublication = $dateOfPublication;

        return $this;
    }*/

    public function getDateOfPublication(): ?string
    {
        return $this->dateOfPublication;
    }

    public function setDateOfPublication(string $dateOfPublication): self
    {
        $this->dateOfPublication = $dateOfPublication;

        return $this;
    }

    /**
     * @return Collection<int, Applicants>
     */
    public function getApplicants(): Collection
    {
        return $this->applicants;
    }

    public function addApplicant(Applicants $applicant): self
    {
        if (!$this->applicants->contains($applicant)) {
            $this->applicants->add($applicant);
            $applicant->addAdvertisement($this);
        }

        return $this;
    }

    public function removeApplicant(Applicants $applicant): self
    {
        if ($this->applicants->removeElement($applicant)) {
            $applicant->removeAdvertisement($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Jobs>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Jobs $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setAdvertisements($this);
        }

        return $this;
    }

    public function removeJob(Jobs $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getAdvertisements() === $this) {
                $job->setAdvertisements(null);
            }
        }

        return $this;
    }
   
}
