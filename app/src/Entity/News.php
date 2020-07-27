<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 * @ORM\Table(name="news")
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string | null
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var DateTime | null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DateTime | null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isHidden;

    /**
     * @var int | null
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $hits;

    /**
     * @param string          $title
     * @param string          $description
     * @param string          $shortDescription
     * @param string | null   $slug
     * @param DateTime | null $updatedAt
     * @param DateTime|null   $publishedAt
     * @param bool            $isActive
     * @param bool            $isHidden
     * @param int | null      $hits
     */
    public function __construct(
        string $title,
        string $description,
        string $shortDescription,
        string $slug = null,
        ?DateTime $updatedAt = null,
        ?DateTime $publishedAt = null,
        bool $isActive = false,
        bool $isHidden = false,
        ?int $hits = null
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->shortDescription = $shortDescription;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
        $this->publishedAt = $publishedAt;
        $this->isActive = $isActive;
        $this->isHidden = $isHidden;
        $this->hits = $hits;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return News
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     *
     * @return News
     */
    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime | null $updatedAt
     *
     * @return News
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTime | null $publishedAt
     *
     * @return News
     */
    public function setPublishedAt(?DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHits(): ?int
    {
        return $this->hits;
    }

    /**
     * @param int | null $hits
     *
     * @return News
     */
    public function setHits(?int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * @param bool $isActive
     *
     * @return self
     */
    public function setActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @param bool $isHidden
     *
     * @return self
     */
    public function setHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * @param string   $title
     * @param string   $description
     * @param string   $shortDescription
     * @param string   $slug
     * @param DateTime $updatedAt
     * @param DateTime $publishedAt
     * @param bool     $isActive
     * @param bool     $isHidden
     *
     * @return self
     */
    public function update(
        string $title,
        string $description,
        string $shortDescription,
        string $slug,
        DateTime $updatedAt,
        DateTime $publishedAt,
        bool $isActive,
        bool $isHidden
    ): self {
        $this->title = $title;
        $this->description = $description;
        $this->shortDescription = $shortDescription;
        $this->slug = $slug;
        $this->updatedAt = $updatedAt;
        $this->publishedAt = $publishedAt;
        $this->isActive = $isActive;
        $this->isHidden = $isHidden;

        return $this;
    }
}