<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111100023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokevolution (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, pre_evolution_id INT DEFAULT NULL, next_evolution_id INT DEFAULT NULL, is_mega_evolution TINYINT(1) DEFAULT NULL, INDEX IDX_11B1E6DF2FE71C3E (pokemon_id), INDEX IDX_11B1E6DFDA97744 (pre_evolution_id), INDEX IDX_11B1E6DF1AF021B0 (next_evolution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFDA97744 FOREIGN KEY (pre_evolution_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF1AF021B0 FOREIGN KEY (next_evolution_id) REFERENCES pokemon (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF2FE71C3E');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFDA97744');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF1AF021B0');
        $this->addSql('DROP TABLE pokevolution');
    }
}
