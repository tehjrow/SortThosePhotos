<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HomeControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndexReturnsCode200()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexShowsLoginIfLoggedOut()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('html button', 'Login');
    }

    public function testIndexShowsSettingsButtonWhenLoggedIn()
    {
        $crawler = $this->client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'test@example.com',
            'PHP_AUTH_PW' => 'password'
        ]);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Settings', $crawler->filter('html button')->text());
    }

    public function testIndexRedirectsWhenIncorrectCredentialsGiven()
    {
        $crawler = $this->client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'test@example.com',
            'PHP_AUTH_PW' => 'wrongpassword'
        ]);

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    }
}