<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619130758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boisson (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE burger (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, livraison_id INT DEFAULT NULL, gestionnaire_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, etat VARCHAR(30) NOT NULL, paye TINYINT(1) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, date DATETIME DEFAULT NULL, numero VARCHAR(255) DEFAULT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D8E54FB25 (livraison_id), INDEX IDX_6EEAA67D6885AC1B (gestionnaire_id), INDEX IDX_6EEAA67D9F2C3FAB (zone_id), UNIQUE INDEX UNIQ_6EEAA67D700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_burger (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, burger_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_EDB7A1D882EA2E54 (commande_id), INDEX IDX_EDB7A1D817CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_frite (id INT AUTO_INCREMENT NOT NULL, frite_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_19831BAEBE00B4D9 (frite_id), INDEX IDX_19831BAE82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, prix INT DEFAULT NULL, INDEX IDX_16693B70CCD7E912 (menu_id), INDEX IDX_16693B7082EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu_taille_boisson (id INT AUTO_INCREMENT NOT NULL, commande_menu_id INT DEFAULT NULL, taille_boisson_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_D72A2B83A6C9BB2 (commande_menu_id), INDEX IDX_D72A2B88421F13F (taille_boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_taille_boisson (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, taille_boisson_id INT DEFAULT NULL, prix INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_9CA1CDB282EA2E54 (commande_id), INDEX IDX_9CA1CDB28421F13F (taille_boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frite (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gestionnaire (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, livreur_id INT DEFAULT NULL, duree TIME DEFAULT NULL, date DATETIME DEFAULT NULL, etat VARCHAR(20) DEFAULT NULL, INDEX IDX_A60C9F1FF8646701 (livreur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livreur (id INT NOT NULL, matricule_moto VARCHAR(50) NOT NULL, is_disponible TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_EB7A4E6DA6A25A62 (matricule_moto), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_burger (id INT AUTO_INCREMENT NOT NULL, burger_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_3CA402D517CE5090 (burger_id), INDEX IDX_3CA402D5CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_frite (id INT AUTO_INCREMENT NOT NULL, frite_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_B147E70ABE00B4D9 (frite_id), INDEX IDX_B147E70ACCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_taille (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, taille_id INT DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_A517D3E0CCD7E912 (menu_id), INDEX IDX_A517D3E0FF25611A (taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, is_etat TINYINT(1) NOT NULL, detail VARCHAR(255) NOT NULL, image LONGBLOB DEFAULT NULL, categorie VARCHAR(50) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_29A5EC276C6E55B5 (nom), INDEX IDX_29A5EC27A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quartier (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, nom VARCHAR(50) NOT NULL, is_etat TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FEE8962D6C6E55B5 (nom), INDEX IDX_FEE8962D9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, prix INT DEFAULT NULL, is_etat TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_76508B386C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_boisson (id INT AUTO_INCREMENT NOT NULL, taille_id INT DEFAULT NULL, boisson_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, quantite_stock INT DEFAULT NULL, image LONGBLOB DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, is_etat TINYINT(1) DEFAULT NULL, INDEX IDX_59FAC268FF25611A (taille_id), INDEX IDX_59FAC268734B8089 (boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(40) NOT NULL, prenom VARCHAR(40) NOT NULL, telephone VARCHAR(30) NOT NULL, is_etat TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, is_activated TINYINT(1) DEFAULT NULL, expired_at DATETIME DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, prix DOUBLE PRECISION NOT NULL, is_etat TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_A0EBC0076C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84DBF396750 FOREIGN KEY (id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE burger ADD CONSTRAINT FK_EFE35A0DBF396750 FOREIGN KEY (id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D8E54FB25 FOREIGN KEY (livraison_id) REFERENCES livraison (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaire (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_burger ADD CONSTRAINT FK_EDB7A1D817CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE commande_frite ADD CONSTRAINT FK_19831BAEBE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id)');
        $this->addSql('ALTER TABLE commande_frite ADD CONSTRAINT FK_19831BAE82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B7082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson ADD CONSTRAINT FK_D72A2B83A6C9BB2 FOREIGN KEY (commande_menu_id) REFERENCES commande_menu (id)');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson ADD CONSTRAINT FK_D72A2B88421F13F FOREIGN KEY (taille_boisson_id) REFERENCES taille_boisson (id)');
        $this->addSql('ALTER TABLE commande_taille_boisson ADD CONSTRAINT FK_9CA1CDB282EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_taille_boisson ADD CONSTRAINT FK_9CA1CDB28421F13F FOREIGN KEY (taille_boisson_id) REFERENCES taille_boisson (id)');
        $this->addSql('ALTER TABLE frite ADD CONSTRAINT FK_20EBC46DBF396750 FOREIGN KEY (id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gestionnaire ADD CONSTRAINT FK_F4461B20BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FF8646701 FOREIGN KEY (livreur_id) REFERENCES livreur (id)');
        $this->addSql('ALTER TABLE livreur ADD CONSTRAINT FK_EB7A4E6DBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93BF396750 FOREIGN KEY (id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_burger ADD CONSTRAINT FK_3CA402D517CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE menu_burger ADD CONSTRAINT FK_3CA402D5CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ABE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id)');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ACCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_taille ADD CONSTRAINT FK_A517D3E0CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_taille ADD CONSTRAINT FK_A517D3E0FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quartier ADD CONSTRAINT FK_FEE8962D9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268FF25611A FOREIGN KEY (taille_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84DBF396750');
        $this->addSql('ALTER TABLE burger DROP FOREIGN KEY FK_EFE35A0DBF396750');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D8E54FB25');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6885AC1B');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9F2C3FAB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D700047D2');
        $this->addSql('ALTER TABLE commande_burger DROP FOREIGN KEY FK_EDB7A1D882EA2E54');
        $this->addSql('ALTER TABLE commande_burger DROP FOREIGN KEY FK_EDB7A1D817CE5090');
        $this->addSql('ALTER TABLE commande_frite DROP FOREIGN KEY FK_19831BAEBE00B4D9');
        $this->addSql('ALTER TABLE commande_frite DROP FOREIGN KEY FK_19831BAE82EA2E54');
        $this->addSql('ALTER TABLE commande_menu DROP FOREIGN KEY FK_16693B70CCD7E912');
        $this->addSql('ALTER TABLE commande_menu DROP FOREIGN KEY FK_16693B7082EA2E54');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson DROP FOREIGN KEY FK_D72A2B83A6C9BB2');
        $this->addSql('ALTER TABLE commande_menu_taille_boisson DROP FOREIGN KEY FK_D72A2B88421F13F');
        $this->addSql('ALTER TABLE commande_taille_boisson DROP FOREIGN KEY FK_9CA1CDB282EA2E54');
        $this->addSql('ALTER TABLE commande_taille_boisson DROP FOREIGN KEY FK_9CA1CDB28421F13F');
        $this->addSql('ALTER TABLE frite DROP FOREIGN KEY FK_20EBC46DBF396750');
        $this->addSql('ALTER TABLE gestionnaire DROP FOREIGN KEY FK_F4461B20BF396750');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FF8646701');
        $this->addSql('ALTER TABLE livreur DROP FOREIGN KEY FK_EB7A4E6DBF396750');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93BF396750');
        $this->addSql('ALTER TABLE menu_burger DROP FOREIGN KEY FK_3CA402D517CE5090');
        $this->addSql('ALTER TABLE menu_burger DROP FOREIGN KEY FK_3CA402D5CCD7E912');
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ABE00B4D9');
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ACCD7E912');
        $this->addSql('ALTER TABLE menu_taille DROP FOREIGN KEY FK_A517D3E0CCD7E912');
        $this->addSql('ALTER TABLE menu_taille DROP FOREIGN KEY FK_A517D3E0FF25611A');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A76ED395');
        $this->addSql('ALTER TABLE quartier DROP FOREIGN KEY FK_FEE8962D9F2C3FAB');
        $this->addSql('ALTER TABLE taille_boisson DROP FOREIGN KEY FK_59FAC268FF25611A');
        $this->addSql('ALTER TABLE taille_boisson DROP FOREIGN KEY FK_59FAC268734B8089');
        $this->addSql('DROP TABLE boisson');
        $this->addSql('DROP TABLE burger');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_burger');
        $this->addSql('DROP TABLE commande_frite');
        $this->addSql('DROP TABLE commande_menu');
        $this->addSql('DROP TABLE commande_menu_taille_boisson');
        $this->addSql('DROP TABLE commande_taille_boisson');
        $this->addSql('DROP TABLE frite');
        $this->addSql('DROP TABLE gestionnaire');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_burger');
        $this->addSql('DROP TABLE menu_frite');
        $this->addSql('DROP TABLE menu_taille');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE quartier');
        $this->addSql('DROP TABLE taille');
        $this->addSql('DROP TABLE taille_boisson');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone');
    }
}
