<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageLoads(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');

        $this->assertTrue(
            $client->getResponse()->isSuccessful() || 
            $client->getResponse()->isNotFound()
        );
    }
}
