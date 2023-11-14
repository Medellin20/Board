<?php

namespace App\Entity;

use App\Repository\ApplicantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicantsRepository::class)]
class Applicants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_ap = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname_ap = null;

    #[ORM\Column(length: 255)]
    private ?string $email_ap = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\ManyToMany(targetEntity: Advertisements::class, inversedBy: 'applicants')]
    private Collection $advertisements;

    #[ORM\OneToOne(mappedBy: 'applicants', cascade: ['persist', 'remove'])]
    private ?Users $users = null;

    #[ORM\ManyToMany(targetEntity: Jobs::class, mappedBy: 'applicants')]
    private Collection $jobs;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAp(): ?string
    {
        return $this->name_ap;
    }

    public function setNameAp(string $name_ap): self
    {
        $this->name_ap = $name_ap;

        return $this;
    }

    public function getFirstnameAp(): ?string
    {
        return $this->firstname_ap;
    }

    public function setFirstnameAp(string $firstname_ap): self
    {
        $this->firstname_ap = $firstname_ap;

        return $this;
    }

    public function getEmailAp(): ?string
    {
        return $this->email_ap;
    }

    public function setEmailAp(string $email_ap): self
    {
        $this->email_ap = $email_ap;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, Advertisements>
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisements $advertisement): self
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements->add($advertisement);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisements $advertisement): self
    {
        $this->advertisements->removeElement($advertisement);

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setApplicants(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getApplicants() !== $this) {
            $users->setApplicants($this);
        }

        $this->users = $users;

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
            $job->addApplicant($this);
        }

        return $this;
    }

    public function removeJob(Jobs $job): self
    {
        if ($this->jobs->removeElement($job)) {
            $job->removeApplicant($this);
        }

        return $this;
    }
}
