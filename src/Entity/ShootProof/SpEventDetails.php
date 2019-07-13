<?php

namespace App\Entity\ShootProof;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShootProof\SpEventDetailsRepository")
 */
class SpEventDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eventId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $brandId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(?int $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function setBrandId(?int $brandId): self
    {
        $this->brandId = $brandId;

        return $this;
    }
}
