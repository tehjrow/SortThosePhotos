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
     * @ORM\Column(type="integer")
     */
    private $eventId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spEventId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spBrandId;

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     */
    public function setEventId($eventId): void
    {
        $this->eventId = $eventId;
    }

    /**
     * @return mixed
     */
    public function getSpEventId()
    {
        return $this->spEventId;
    }

    /**
     * @param mixed $spEventId
     */
    public function setSpEventId($spEventId): void
    {
        $this->spEventId = $spEventId;
    }

    /**
     * @return mixed
     */
    public function getSpBrandId()
    {
        return $this->spBrandId;
    }

    /**
     * @param mixed $spBrandId
     */
    public function setSpBrandId($spBrandId): void
    {
        $this->spBrandId = $spBrandId;
    }


}
