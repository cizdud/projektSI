<?php
/**
 * Contact entity.
 */

namespace App\Entity;

use App\Repository\ContactRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Class Contact.
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contacts')]
class Contact
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    #[Assert\Regex('/^[a-zA-Z]+$/')]
    private ?string $name;

    /**
     * Surname.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    #[Assert\Regex('/^[a-zA-Z]+$/')]
    private ?string $surname;

    /**
     * Address.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(min: 3)]
    private ?string $address;

    /**
     * Phone.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(min: 3)]
    #[Assert\Regex('/^[0-9]*$/')]
    private ?string $phone;

    /**
     * Created at.
     *
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Updated at.
     *
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name Name
     *
     * @return Contact
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for Surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string|null $surname Surname
     *
     * @return Contact
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Getter for Address.
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Setter for Address.
     *
     * @param string|null $address Address
     *
     * @return Contact
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Getter for Phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Setter for Phone.
     *
     * @param string|null $phone Phone
     *
     * @return Contact
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the created at date.
     *
     * @param \DateTimeImmutable|null $createdAt The created at date
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        if (null === $createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        } else {
            $this->createdAt = $createdAt;
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the updated at date.
     *
     * @param \DateTimeImmutable|null $updatedAt The updated at date
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        if (null === $updatedAt) {
            $this->updatedAt = new \DateTimeImmutable();
        } else {
            $this->updatedAt = $updatedAt;
        }
    }
}
