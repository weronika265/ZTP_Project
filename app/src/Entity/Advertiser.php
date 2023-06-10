<?php

/**
 * Advertiser entity.
 */

namespace App\Entity;

use App\Repository\AdvertiserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Advertiser.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: AdvertiserRepository::class)]
#[ORM\Table(name: 'advertisers')]
class Advertiser
{
    /**
     * Primary key.
     *
     * @var int|null Id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     *
     * @var string|null Email
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\Length(max: 50)]
    private ?string $email = null;

    /**
     * Phone number.
     *
     * @var string|null Phone
     */
    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 15)]
    private ?string $phone = null;

    /**
     * Advertiser name.
     *
     * @var string|null Name
     */
    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 45)]
    private ?string $name = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for phone number.
     *
     * @return string|null Phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Setter for phone number.
     *
     * @param string|null $phone Phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * Getter for advertiser name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for advertiser name.
     *
     * @param string|null $name Name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
