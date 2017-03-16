<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PriceControllerTest extends WebTestCase
{
    public function testGetprices()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/places/{id}/prices');
    }

    public function testPostprices()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/places/{id}/prices');
    }

}
