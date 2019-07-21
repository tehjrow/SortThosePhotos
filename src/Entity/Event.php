<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasUploadedCsv = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasDownloadedQrCodes = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasUploadedImages = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasPublishedToService = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $csvFilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getHasUploadedCsv(): ?bool
    {
        return $this->hasUploadedCsv;
    }

    public function setHasUploadedCsv(bool $hasUploadedCsv): self
    {
        $this->hasUploadedCsv = $hasUploadedCsv;

        return $this;
    }

    public function getHasDownloadedQrCodes(): ?bool
    {
        return $this->hasDownloadedQrCodes;
    }

    public function setHasDownloadedQrCodes(bool $hasDownloadedQrCodes): self
    {
        $this->hasDownloadedQrCodes = $hasDownloadedQrCodes;

        return $this;
    }

    public function getHasUploadedImages(): ?bool
    {
        return $this->hasUploadedImages;
    }

    public function setHasUploadedImages(bool $hasUploadedImages): self
    {
        $this->hasUploadedImages = $hasUploadedImages;

        return $this;
    }

    public function getHasPublishedToService(): ?bool
    {
        return $this->hasPublishedToService;
    }

    public function setHasPublishedToService(bool $hasPublishedToService): self
    {
        $this->hasPublishedToService = $hasPublishedToService;

        return $this;
    }

    public function getCsvFilename(): ?string
    {
        return $this->csvFilename;
    }

    public function setCsvFilename(?string $csvFilename): self
    {
        $this->csvFilename = $csvFilename;

        return $this;
    }
}
