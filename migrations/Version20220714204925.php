<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714204925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_burger (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, burger_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_EDB7A1D882EA2E54 (commande_id), INDEX IDX_EDB7A1D817CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_frite (id INT AUTO_INCREMENT NOT NULL, frite_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_19831BAEBE00B4D9 (frite_id), INDEX IDX_19831BAE82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_16693B70CCD7E912 (menu_id), INDEX IDX_16693B7082EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D817CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE commande_frite ADD CONSTRAINT FK_19831BAEBE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id)');
        $this->addSql('ALTER TABLE commande_frite ADD CONSTRAINT FK_19831BAE82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B7082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande_burger');
        $this->addSql('DROP TABLE commande_frite');
        $this->addSql('DROP TABLE commande_menu');
    }
}
