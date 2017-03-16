<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaceControllerTest extends WebTestCase
{
    public function testGetplaces()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/palces');
    }

    public function testGetplace()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/place/{id}');
    }

}
