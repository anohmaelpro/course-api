<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cette email existe déjà ! Veiller saisir une nouvelle adresse mail"
 * )
 * @ApiResource(
 *      denormalizationContext={"disable_type_enforcement"=true},
 *      normalizationContext={"groups"={"users_read"}}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customers_read", "invoices_read", "invoices_subresource", "users_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"customers_read", "invoices_read", "invoices_subresource", "users_read"})
     * @Assert\Email(message="Votre Email  {{ value }} est invalide")
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir votre email s'il vous plait")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"customers_read", "invoices_subresource", "users_read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir votre mot de passe s'il vous plait")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read", "invoices_read", "invoices_subresource", "users_read"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir votre prénom s'il vous plait")
     * @Assert\Length(min="2", minMessage="Votre prénom saisie est trop court. 2 caractères minimum",  max="255", maxMessage="Le prénom est trop long. 20 caractères maximum")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customers_read", "invoices_read", "invoices_subresource", "users_read"})
     * @Assert\NotBlank(message="Ce champ est obligatoire, Veiller saisir votre Nom s'il vous plait")
     * @Assert\Length(min="2", minMessage="Votre Nom saisie est trop court. 2 caractères minimum",  max="20", maxMessage="Le Nom est trop long. 20 caractères maximum")
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="userCustomer")
     */
    private $customerUser;

    public function __construct()
    {
        $this->customerUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles = ['ROLE_USER'];

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection|Customer[]
     */
    public function getCustomerUser(): Collection
    {
        return $this->customerUser;
    }

    public function addCustomerUser(Customer $customerUser): self
    {
        if (!$this->customerUser->contains($customerUser)) {
            $this->customerUser[] = $customerUser;
            $customerUser->setUserCustomer($this);
        }

        return $this;
    }

    public function removeCustomerUser(Customer $customerUser): self
    {
        if ($this->customerUser->removeElement($customerUser)) {
            // set the owning side to null (unless already changed)
            if ($customerUser->getUserCustomer() === $this) {
                $customerUser->setUserCustomer(null);
            }
        }

        return $this;
    }
}
