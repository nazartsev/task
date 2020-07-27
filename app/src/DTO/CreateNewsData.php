<?php

namespace App\DTO;

use DateTime;

class CreateNewsData
{
    /**
     * @var string
     */
    private$title;

    /**
     * @var string
     */
    private$slug;

    /**
     * @var string
     */
    private$description;

    /**
     * @var string
     */
    private$shortDescription;

    /**
     * @var DateTime
     */
    private$publishedAt;

    /**
     * @var bool
     */
    private$isActive;

    /**
     * @var bool
     */
    private$isHidden;

    /**
     * @param string   $title
     * @param string   $slug
     * @param string   $description
     * @param string   $shortDescription
     * @param DateTime $publishedAt
     * @param bool     $isActive
     * @param bool     $isHidden
     */
    public function __construct(
        string $title,
        string $slug,
        string $description,
        string $shortDescription,
        DateTime $publishedAt,
        bool $isActive,
        bool $isHidden
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->shortDescription = $shortDescription;
        $this->publishedAt = $publishedAt;
        $this->isActive = $isActive;
        $this->isHidden = $isHidden;
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
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }
}
