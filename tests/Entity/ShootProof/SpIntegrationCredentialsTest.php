<?php


namespace App\Tests\Entity\ShootProof;

use App\Entity\ShootProof\SpIntegrationCredentials;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SpIntegrationCredentialsTest extends WebTestCase
{
    public function testCreateSpIntegrationCredentials()
    {
        $spIntegrationCredentials = new SpIntegrationCredentials();
        $spIntegrationCredentials->setScope('studio');
        $spIntegrationCredentials->setUserId(4);
        $spIntegrationCredentials->setAccessToken('ACCESSTOKEN343434');
        $spIntegrationCredentials->setExpiresIn('01003404');
        $spIntegrationCredentials->setRefreshToken('REFRESHTOKEN454545');
        $spIntegrationCredentials->setStat('OK');
        $spIntegrationCredentials->setTokenType('access_token');

        $this->assertEquals('studio', $spIntegrationCredentials->getScope());
        $this->assertEquals(4, $spIntegrationCredentials->getUserId());
        $this->assertEquals('ACCESSTOKEN343434', $spIntegrationCredentials->getAccessToken());
        $this->assertEquals('01003404', $spIntegrationCredentials->getExpiresIn());
        $this->assertEquals('REFRESHTOKEN454545', $spIntegrationCredentials->getRefreshToken());
        $this->assertEquals('OK', $spIntegrationCredentials->getStat());
        $this->assertEquals('access_token', $spIntegrationCredentials->getTokenType());
    }
}