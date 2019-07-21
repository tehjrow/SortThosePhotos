<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Tests\Entity;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventTest extends WebTestCase
{
    public function testCreateEvent()
    {
        $event = new Event();
        $event->setName('Test School');
        $event->setUserId(4);

        $this->assertEquals(null, $event->getId());
        $this->assertEquals('Test School', $event->getName());
        $this->assertEquals(4, $event->getUserId());
        $this->assertEquals(false, $event->getHasDownloadedQrCodes());
        $this->assertEquals(false, $event->getHasPublishedToService());
        $this->assertEquals(false, $event->getHasUploadedCsv());
        $this->assertEquals(false, $event->getHasUploadedImages());
    }

    public function testSetFlagsToTrue()
    {
        $event = new Event();
        $event->setName('Test School');
        $event->setUserId(4);
        $event->setHasDownloadedQrCodes(true);
        $event->setHasPublishedToService(true);
        $event->setHasUploadedCsv(true);
        $event->setHasUploadedImages(true);

        $this->assertEquals(true, $event->getHasDownloadedQrCodes());
        $this->assertEquals(true, $event->getHasPublishedToService());
        $this->assertEquals(true, $event->getHasUploadedCsv());
        $this->assertEquals(true, $event->getHasUploadedImages());
    }
}