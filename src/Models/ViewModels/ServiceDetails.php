<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Models\ViewModels;


use App\Entity\ShootProof\SpEventDetails;

class ServiceDetails
{
    private $_spEventDetails;

    public function __construct(SpEventDetails $spEventDetails)
    {
        $this->_spEventDetails = $spEventDetails;
    }

    /**
     * @return SpEventDetails
     */
    public function getSpEventDetails(): SpEventDetails
    {
        return $this->_spEventDetails;
    }

    /**
     * @param SpEventDetails $spEventDetails
     */
    public function setSpEventDetails(SpEventDetails $spEventDetails): void
    {
        $this->_spEventDetails = $spEventDetails;
    }
}