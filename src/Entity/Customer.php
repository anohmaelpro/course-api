<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ApiResource(
 *      attributes={"pagination_enabled"=true},
 *      normalizationContext={"groups"={"customers_read"}},
 *      subresourceOperations={
 *              "invoices_customers_get_subresource" = {"path"="/customers/{id}/invoices_customers"}
 *      },
 *      collectionOperations={"GET", "POST"},
 *      itemOperations={"GET", "PUT", "DELETE", "PATCH"},
 * 
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customers_read", "invoices_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read", "invoices_read"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read", "invoices_read"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read", "invoices_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customers_read", "invoices_read"})
     */
    private $company;

    /**
     * les factures par client
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="customer")
     * @Groups({"customers_read"})
     * @ApiSubresource
     */
    private $invoicesCustomer;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customerUser")
     * @Groups({"customers_read"})
     */
    private $userCustomer;

    public function __construct()
    {
        $this->invoicesCustomer = new ArrayCollection();
    }


    /**
     * cette fonction retourne  un float qui est me total des factures amount  = amout 
     * @Groups({"customers_read"})
     * @return float
     */
    public function getTotalAmount():float
    {
        return array_reduce($this->invoicesCustomer->toArray(), function($total, $invoicesCustomer){
            return $total + $invoicesCustomer->getAmout();
        }, 0);
    }

    /**
     * cette fonction permet de récupéré la somme dû du client
     * @Groups({"customers_read"})
     * @return float
     */
    public function getUnPaidAmount():float
    {
        return array_reduce($this->invoicesCustomer->toArray(), function($total, $invoicesCustomer){
            return $total + ($invoicesCustomer->getStatus() === "PAID" || $invoicesCustomer->getStatus() === "CANCELLED" ? 0 : $invoicesCustomer->getAmout()) ;
        }, 0);
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
