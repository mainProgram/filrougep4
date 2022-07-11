<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705210607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_taille_boisson');
        $this->addSql('ALTER TABLE taille ADD prix INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_taille_boisson (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, taille_boissons_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_4030374CCCD7E912 (menu_id), INDEX IDX_4030374C5193B1E9 (taille_boissons_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE menu_taille_boisson ADD CONSTRAINT FK_4030374CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_taille_boisson ADD CONSTRAINT FK_4030374C5193B1E9 FOREIGN KEY (taille_boissons_id) REFERENCES taille_boisson (id)');
        $this->addSql('ALTER TABLE taille DROP prix');
    }
}
