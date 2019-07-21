<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Tests\Entity\ShootProof;


use App\Entity\ShootProof\SpEventDetails;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpEventDetailsTest extends WebTestCase
{
    public function testCreateSpEventDetails()
    {
        $spEventDetails = new spEventDetails();
        $spEventDetails->setEventId('3252352');
        $spEventDetails->setSpBrandId('5757575');
        $spEventDetails->setSpEventId('2353245');

        $this->assertEquals('3252352', $spEventDetails->getEventId());
        $this->assertEquals('5757575', $spEventDetails->getSpBrandId());
        $this->assertEquals('2353245', $spEventDetails->getSpEventId());
    }
}