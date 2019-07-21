<?php

/**
 * SortThosePhotos
 *
 * A tool to help high volume photographers sort their photos
 */

namespace App\Tests\Entity\ShootProof;

use App\Entity\ShootProof\SpAppCredentials;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpAppCredentialsTest extends WebTestCase
{
    public function testCreateSpAppCredentials()
    {
        $spAppCredentials = new SpAppCredentials();
        $spAppCredentials->setClientId('FHE483ASHFAUEH34798234');
        $spAppCredentials->setRedirectUri('https://www.example.com');
        $spAppCredentials->setResponseType('code');
        $spAppCredentials->setScope('studio');
        $spAppCredentials->setState('OK');

        $this->assertEquals(null, $spAppCredentials->getId());
        $this->assertEquals('FHE483ASHFAUEH34798234', $spAppCredentials->getClientId());
        $this->assertEquals('https://www.example.com', $spAppCredentials->getRedirectUri());
        $this->assertEquals('code', $spAppCredentials->getResponseType());
        $this->assertEquals('studio', $spAppCredentials->getScope());
        $this->assertEquals('OK', $spAppCredentials->getState());
    }
}