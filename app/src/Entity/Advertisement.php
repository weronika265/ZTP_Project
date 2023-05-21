<?php

/**
 * Advertisement entity.
 */

namespace App\Entity;

use App\Repository\AdvertisementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Advertisement.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[ORM\Table('advertisements')]
class Advertisement
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
     * Name of ad.
     *
     * @var string|null Name
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    /**
     * Description.
     *
     * @var string|null Description
     */
    #[ORM\Column(type: 'string', length: 2000)]
    private ?string $description = null;

    /**
     * Price.
     *
     * @var string|null Price
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    /**
     * Location.
     *
     * @var string|null Location
     */
    #[ORM\Column(type: 'string', length: 45)]
    private ?string $location = null;

    /**
     * Date.
     *
     * @var \DateTimeInterface|null Date
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * Is active.
     *
     * @var bool|null Is active
     */
    #[ORM\Column]
    private ?bool $is_active = null;

    /**
     * Category.
     *
     * @var \App\Entity\Category|null Category entity
     */
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Advertiser.
     *
     * @var \App\Entity\Advertiser|null Advertiser entity
     */
    #[ORM\ManyToOne(targetEntity: Advertiser::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Advertiser $advertiser = null;

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
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for price.
     *
     * @return string|null Price
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Setter for price.
     *
     * @param string|null $price Price
     */
    public function setPrice(?string $price): void
    {
        $this->price = $price;
    }

    /**
     * Getter for location.
     *
     * @return string|null Location
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Setter for location.
     *
     * @param string $location Location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * Getter for date.
     *
     * @return \DateTimeInterface|null Date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for date.
     *
     * @param \DateTimeInterface $date Date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * Getter for is active.
     *
     * @return bool|null Is active
     */
    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    /**
     * Setter for is active.
     *
     * @param bool $is_active Is active
     */
    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null Category entity
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category entity
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for Advertiser.
     *
     * @return Advertiser|null Advertiser entity
     */
    public function getAdvertiser(): ?Advertiser
    {
        return $this->advertiser;
    }

    /**
     * Setter for Advertiser.
     *
     * @param Advertiser|null $advertiser Advertiser entity
     */
    public function setAdvertiser(?Advertiser $advertiser): void
    {
        $this->advertiser = $advertiser;
    }
}
