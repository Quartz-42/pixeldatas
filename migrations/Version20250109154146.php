<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109154146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE talent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE talent_pokemon (talent_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_EC11554C18777CEF (talent_id), INDEX IDX_EC11554C2FE71C3E (pokemon_id), PRIMARY KEY(talent_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE talent_pokemon ADD CONSTRAINT FK_EC11554C18777CEF FOREIGN KEY (talent_id) REFERENCES talent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE talent_pokemon ADD CONSTRAINT FK_EC11554C2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE talent_pokemon DROP FOREIGN KEY FK_EC11554C18777CEF');
        $this->addSql('ALTER TABLE talent_pokemon DROP FOREIGN KEY FK_EC11554C2FE71C3E');
        $this->addSql('DROP TABLE talent');
        $this->addSql('DROP TABLE talent_pokemon');
    }
}
