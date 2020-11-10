<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "items_per_page"=20,
 *          "order"={"sentAt": "desc" }
 *      },
 *  itemOperations={"GET", "PUT", "DELETE", "PATCH", "increment"={
 *          "method"="post",
 *          "path"="/invoices/{id}/increment",
 *          "controller"="App\Controller\invoiceIncrementationController",
 *          "swagger_context"={
 *              "summary"="Incrémente une facture",
 *              "description"="Incrémente le chrono d'une facture donnée"} 
 *      }
 *  },
 *  subresourceOperations={
 *          "api_customers_invoices_customers_get_subresource"={
 *                     "normalization_context"={"groups"={"invoices_subresource"}}
 *          }
 *  },
 *  normalizationContext={"groups"={"invoices_read"}},
 *  denormalizationContext={"disable_type_enforcement"=true}
 * )
 * 
 * @ApiFilter(OrderFilter::class, properties={"amout", "sentAt"})
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "customers_read", "invoices_subresource"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"invoices_read", "customers_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir le montant s'il vous plait")
     * @Assert\Type(type="numeric" , message="Le montant doit être un numérique")
     * @Assert\Positive(message="Cette valeur  doit être positive et non nulle")
     */
    private $amout;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"invoices_read", "customers_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir la date au format YYYY-MM-DD s'il vous plait")
     * @Assert\type( type="\DateTime", message="Il faut entrer une date format Année-Mois-Jour")
     */
    private $sentAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"invoices_read", "customers_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir le status de la facture s'il vous plait soit  SENT, CANCELLED, PAID")
     * @Assert\Choice(choices={"SENT","PAID","CANCELLED"}, message="le statut doit être SENT, CANCELLED, PAID")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="invoicesCustomer")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoices_read"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller entrer un customer s'il vous plait")
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"invoices_read", "customers_read", "invoices_subresource"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir le numéro de l'invoice s'il vous plait")
     * @Assert\Positive(message="Cette valeur  doit être positive et non nulle")
     * @Assert\Type(type="integer", message="La valeur doit être un entier positif")
     */
    private $chrono;


    /**
     * cette foonction permet de savoir quel user est rattacher à une factures de customer
     *  @Groups({"invoices_read", "invoices_subresource"})
     * @return User
     */
    public function getUserInvoice():User
    {
        return $this->customer->getUserCustomer();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmout(): ?float
    {
        return $this->amout;
    }

    // le type float à été rétirer afin de permettre que la validation se fasse par API resource, ainsi un message d'erreur plus évident est possible.
    public function setAmout( $amout): self
    {
        $this->amout = $amout;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    // le type \DateTime à été rétirer afin de permettre que la validation se fasse par API resource, ainsi un message d'erreur plus évident est possible.
    public function setSentAt($sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    // le type int à été rétirer afin de permettre que la validation se fasse par API resource, ainsi un message d'erreur plus évident est possible.
    public function setChrono($chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }
}
