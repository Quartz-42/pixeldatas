<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112124636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, pokedex_id INT NOT NULL, generation INT NOT NULL, category VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, sprite_regular VARCHAR(255) NOT NULL, sprite_shiny VARCHAR(255) DEFAULT NULL, sprite_gmax VARCHAR(255) DEFAULT NULL, sprite_gmax_shiny VARCHAR(255) DEFAULT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spe_atk INT NOT NULL, spe_def INT NOT NULL, vit INT NOT NULL, height VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokevolution (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, pre_evolution_id INT DEFAULT NULL, next_evolution_id INT DEFAULT NULL, is_mega_evolution TINYINT(1) DEFAULT NULL, INDEX IDX_11B1E6DF2FE71C3E (pokemon_id), INDEX IDX_11B1E6DFDA97744 (pre_evolution_id), INDEX IDX_11B1E6DF1AF021B0 (next_evolution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE talent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX unique_type_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE talent_pokemon (talent_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_EC11554C18777CEF (talent_id), INDEX IDX_EC11554C2FE71C3E (pokemon_id), PRIMARY KEY(talent_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX unique_type_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_pokemon (type_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_4AFDFF06C54C8C93 (type_id), INDEX IDX_4AFDFF062FE71C3E (pokemon_id), PRIMARY KEY(type_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFDA97744 FOREIGN KEY (pre_evolution_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF1AF021B0 FOREIGN KEY (next_evolution_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE talent_pokemon ADD CONSTRAINT FK_EC11554C18777CEF FOREIGN KEY (talent_id) REFERENCES talent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE talent_pokemon ADD CONSTRAINT FK_EC11554C2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_pokemon ADD CONSTRAINT FK_4AFDFF06C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_pokemon ADD CONSTRAINT FK_4AFDFF062FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF2FE71C3E');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFDA97744');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF1AF021B0');
        $this->addSql('ALTER TABLE talent_pokemon DROP FOREIGN KEY FK_EC11554C18777CEF');
        $this->addSql('ALTER TABLE talent_pokemon DROP FOREIGN KEY FK_EC11554C2FE71C3E');
        $this->addSql('ALTER TABLE type_pokemon DROP FOREIGN KEY FK_4AFDFF06C54C8C93');
        $this->addSql('ALTER TABLE type_pokemon DROP FOREIGN KEY FK_4AFDFF062FE71C3E');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokevolution');
        $this->addSql('DROP TABLE talent');
        $this->addSql('DROP TABLE talent_pokemon');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_pokemon');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
