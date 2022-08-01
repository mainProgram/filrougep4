<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714214725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_menu_taille_boisson (id INT AUTO_INCREMENT NOT NULL, commande_menu_id INT DEFAULT NULL, taille_boisson_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_D72A2B83A6C9BB2 (commande_menu_id), INDEX IDX_D72A2B88421F13F (taille_boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson ADD CONSTRAINT FK_D72A2B83A6C9BB2 FOREIGN KEY (commande_menu_id) REFERENCES commande_menu (id)');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson ADD CONSTRAINT FK_D72A2B88421F13F FOREIGN KEY (taille_boisson_id) REFERENCES taille_boisson (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande_menu_taille_boisson');
    }
}
