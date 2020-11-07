<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=true
 *  }
 * )
 * @ApiFilter(SearchFilter::class,  properties={"firstName", "lastName", "company"})
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="customer")
     */
    private $invoicesCustomer;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customurUser")
     */
    private $userCustomer;

    public function __construct()
    {
        $this->invoicesCustomer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoicesCustomer(): Collection
    {
        return $this->invoicesCustomer;
    }

    public function addInvoicesCustomer(Invoice $invoicesCustomer): self
    {
        if (!$this->invoicesCustomer->contains($invoicesCustomer)) {
            $this->invoicesCustomer[] = $invoicesCustomer;
            $invoicesCustomer->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoicesCustomer(Invoice $invoicesCustomer): self
    {
        if ($this->invoicesCustomer->removeElement($invoicesCustomer)) {
            // set the owning side to null (unless already changed)
            if ($invoicesCustomer->getCustomer() === $this) {
                $invoicesCustomer->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUserCustomer(): ?User
    {
        return $this->userCustomer;
    }

    public function setUserCustomer(?User $userCustomer): self
    {
        $this->userCustomer = $userCustomer;

        return $this;
    }
}
