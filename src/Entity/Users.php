<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @ORM\Table(name="`users`")
 * @UniqueEntity(fields={"email"},message="un compte existe déjà à cette adresse mail")
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Email(message="Email invalide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez renseigner ce champs")
     
     * @Assert\Length(min=5, minMessage="Votre mot de passe doit contenir au moins 5 caractères")

     */
    private $password;

    /**
     *  @Assert\EqualTo(propertyPath="password", message="Les mots de passes ne correspondent pas")
     */
    public $confirmPassword;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ['user'];
    public function __toString() {
        return $this->name;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

   

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=5, minMessage="Votre code secret doit contenir au moins 5 caractères")
     */
    private $code_office;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     */
    private $office;

    /**
     * @ORM\OneToMany(targetEntity=Participations::class, mappedBy="user", cascade={"remove"})
     */
    private $participations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

  

  

    public function __construct()
    {
        $this->office = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->participations = new ArrayCollection();
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

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
       return $this->email;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getUserIdentifier()
    {
        
        return $this->getEmail();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getCodeOffice(): ?string
    {
        return $this->code_office;
    }

    public function setCodeOffice(?string $code_office): self
    {
        $this->code_office = $code_office;

        return $this;
    }

    public function getOffice()
    {
        return $this->office;
    }

    public function setOffice(?self $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getId()
    {

        return $this->id;

    }

    /**
     * @return Collection|Participations[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participations $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participations $participation): self
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

  
 


   


}
