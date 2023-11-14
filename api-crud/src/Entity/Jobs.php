<?php

namespace App\Entity;

use App\Repository\JobsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobsRepository::class)]
class Jobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'jobs', targetEntity:'Companies')]
    #[ORM\JoinColumn(nullable: false, name:"companies_id",referencedColumnName:"id")]
    private ?Companies $companies = null;

    #[ORM\ManyToMany(targetEntity: 'Applicants', inversedBy: 'jobs')]
    private Collection $applicants;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false, name:"advertisements_id",referencedColumnName:"id")]
    private ?Advertisements $advertisements = null;

    #[ORM\Column(length: 255)]
    private ?string $email_sent_companies = null;

    #[ORM\Column(length: 255)]
    private ?string $email_sent_apply = null;

    public function __construct()
    {
        $this->applicants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
        }

        return $this;
    }

    public function removeApplicant(Applicants $applicant): self
    {
        $this->applicants->removeElement($applicant);

        return $this;
    }

    public function getAdvertisements(): ?Advertisements
    {
        return $this->advertisements;
    }

    public function setAdvertisements(?Advertisements $advertisements): self
    {
        $this->advertisements = $advertisements;

        return $this;
    }

    public function getEmailSentCompanies(): ?string
    {
        return $this->email_sent_companies;
    }

    public function setEmailSentCompanies(string $email_sent_companies): self
    {
        $this->email_sent_companies = $email_sent_companies;

        return $this;
    }

    public function getEmailSentApply(): ?string
    {
        return $this->email_sent_apply;
    }

    public function setEmailSentApply(string $email_sent_apply): self
    {
        $this->email_sent_apply = $email_sent_apply;

        return $this;
    }
}
