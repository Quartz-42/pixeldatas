<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112140929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF1AF021B0');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFDA97744');
        $this->addSql('DROP INDEX IDX_11B1E6DF1AF021B0 ON pokevolution');
        $this->addSql('DROP INDEX IDX_11B1E6DFDA97744 ON pokevolution');
        $this->addSql('ALTER TABLE pokevolution ADD pre_evolution1_id INT DEFAULT NULL, ADD pre_evolution2_id INT DEFAULT NULL, ADD next_evolution1_id INT DEFAULT NULL, ADD next_evolution2_id INT DEFAULT NULL, DROP pre_evolution_id, DROP next_evolution_id');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFA7D0016D FOREIGN KEY (pre_evolution1_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFB565AE83 FOREIGN KEY (pre_evolution2_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF1D176E3E FOREIGN KEY (next_evolution1_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFFA2C1D0 FOREIGN KEY (next_evolution2_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_11B1E6DFA7D0016D ON pokevolution (pre_evolution1_id)');
        $this->addSql('CREATE INDEX IDX_11B1E6DFB565AE83 ON pokevolution (pre_evolution2_id)');
        $this->addSql('CREATE INDEX IDX_11B1E6DF1D176E3E ON pokevolution (next_evolution1_id)');
        $this->addSql('CREATE INDEX IDX_11B1E6DFFA2C1D0 ON pokevolution (next_evolution2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFA7D0016D');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFB565AE83');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DF1D176E3E');
        $this->addSql('ALTER TABLE pokevolution DROP FOREIGN KEY FK_11B1E6DFFA2C1D0');
        $this->addSql('DROP INDEX IDX_11B1E6DFA7D0016D ON pokevolution');
        $this->addSql('DROP INDEX IDX_11B1E6DFB565AE83 ON pokevolution');
        $this->addSql('DROP INDEX IDX_11B1E6DF1D176E3E ON pokevolution');
        $this->addSql('DROP INDEX IDX_11B1E6DFFA2C1D0 ON pokevolution');
        $this->addSql('ALTER TABLE pokevolution ADD pre_evolution_id INT DEFAULT NULL, ADD next_evolution_id INT DEFAULT NULL, DROP pre_evolution1_id, DROP pre_evolution2_id, DROP next_evolution1_id, DROP next_evolution2_id');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DF1AF021B0 FOREIGN KEY (next_evolution_id) REFERENCES pokemon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE pokevolution ADD CONSTRAINT FK_11B1E6DFDA97744 FOREIGN KEY (pre_evolution_id) REFERENCES pokemon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_11B1E6DF1AF021B0 ON pokevolution (next_evolution_id)');
        $this->addSql('CREATE INDEX IDX_11B1E6DFDA97744 ON pokevolution (pre_evolution_id)');
    }
}
