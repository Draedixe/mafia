<?php

namespace Mafia\PartieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/parametres/liste');

        $this->assertTrue($crawler->filter('html:contains("Connexion")')->count() > 0);
    }
}
