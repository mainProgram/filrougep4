<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707090043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taille_boisson DROP FOREIGN KEY FK_59FAC268734B8089');
        $this->addSql('ALTER TABLE taille_boisson DROP FOREIGN KEY FK_59FAC268FF25611A');
        $this->addSql('DROP INDEX IDX_59FAC268FF25611A ON taille_boisson');
        $this->addSql('DROP INDEX IDX_59FAC268734B8089 ON taille_boisson');
        $this->addSql('ALTER TABLE taille_boisson DROP taille_id, DROP boisson_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taille_boisson ADD taille_id INT DEFAULT NULL, ADD boisson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('CREATE INDEX IDX_59FAC268FF25611A ON taille_boisson (taille_id)');
        $this->addSql('CREATE INDEX IDX_59FAC268734B8089 ON taille_boisson (boisson_id)');
    }
}
