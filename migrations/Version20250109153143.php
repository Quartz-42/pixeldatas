<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109153143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, pokedex_id INT NOT NULL, generation INT NOT NULL, category VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, sprite_regular VARCHAR(255) NOT NULL, sprite_shiny VARCHAR(255) DEFAULT NULL, sprite_gmax VARCHAR(255) DEFAULT NULL, sprite_gmax_shiny VARCHAR(255) DEFAULT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spe_atk INT NOT NULL, spe_def INT NOT NULL, vit INT NOT NULL, height VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokemon');
    }
}
