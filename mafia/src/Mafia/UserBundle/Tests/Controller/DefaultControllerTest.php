<?php

namespace Mafia\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $buttonCrawlerNode = $crawler->selectButton('_submit');

        $form = $buttonCrawlerNode->form();

        $client->submit($form, array(
            '_username'              => 'Raph55',
            '_password'  => 'abc',
        ));

        $crawler = $client->submit($form);

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Connecté en tant que Raph55")')->count() > 0);

        $crawler = $client->request('GET', '/parametres/liste');

        $this->assertTrue($crawler->filter('html:contains("Liste des paramètres")')->count() > 0);
    }
}
