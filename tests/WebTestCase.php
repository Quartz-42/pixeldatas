<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * Base class pour les tests fonctionnels
 * Fournit des méthodes utilitaires communes
 */
abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * Créer un client avec des options par défaut
     */
    protected function createAuthenticatedClient(?string $username = null): object
    {
        $client = static::createClient();
       
        return $client;
    }

    /**
     * Helper pour vérifier qu'un sélecteur CSS contient du texte
     */
    protected function assertSelectorContainsText(string $selector, string $text, object $client): void
    {
        $crawler = $client->getCrawler();
        $element = $crawler->filter($selector);
        
        $this->assertGreaterThan(
            0, 
            $element->count(), 
            "Le sélecteur '{$selector}' n'a pas été trouvé"
        );
        
        $this->assertStringContainsString(
            $text, 
            $element->text(), 
            "Le texte '{$text}' n'a pas été trouvé dans le sélecteur '{$selector}'"
        );
    }

    /**
     * Helper pour suivre un lien par son texte
     */
    protected function clickLinkByText(object $client, string $linkText): object
    {
        $crawler = $client->getCrawler();
        $link = $crawler->selectLink($linkText)->link();
        return $client->click($link);
    }
}
