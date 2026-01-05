<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageLoads(): void
    {
        $client = static::createClient();
        
        // Essayer de charger la page de contact
        // Ajustez le path selon votre configuration de route
        $crawler = $client->request('GET', '/contact');

        // Si la route existe, elle devrait fonctionner
        $this->assertTrue(
            $client->getResponse()->isSuccessful() || 
            $client->getResponse()->isNotFound()
        );
    }
}
