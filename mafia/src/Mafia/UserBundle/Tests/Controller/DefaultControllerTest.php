<?php

namespace Mafia\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/menu');

        $this->assertTrue($crawler->filter('html:contains("Raph55")')->count() > 0);
    }
}
