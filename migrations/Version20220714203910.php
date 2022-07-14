<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714203910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_produit_taille_boisson DROP FOREIGN KEY FK_1E96E6BF97F6521D');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE commande_produit_taille_boisson');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_produit (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, quantite INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande_produit_taille_boisson (commande_produit_id INT NOT NULL, taille_boisson_id INT NOT NULL, INDEX IDX_1E96E6BF8421F13F (taille_boisson_id), INDEX IDX_1E96E6BF97F6521D (commande_produit_id), PRIMARY KEY(commande_produit_id, taille_boisson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_produit_taille_boisson ADD CONSTRAINT FK_1E96E6BF97F6521D FOREIGN KEY (commande_produit_id) REFERENCES commande_produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit_taille_boisson ADD CONSTRAINT FK_1E96E6BF8421F13F FOREIGN KEY (taille_boisson_id) REFERENCES taille_boisson (id) ON DELETE CASCADE');
    }
}
