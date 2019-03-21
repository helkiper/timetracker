<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TimerControllerTest extends WebTestCase
{
    public function testStart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/start');
    }

    public function testStop()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/stop');
    }

    public function testState()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/state');
    }

}
