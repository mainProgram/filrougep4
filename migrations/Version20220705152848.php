<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705152848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ACCD7E912');
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ABE00B4D9');
        $this->addSql('ALTER TABLE menu_frite ADD id INT AUTO_INCREMENT NOT NULL, CHANGE menu_id menu_id INT DEFAULT NULL, CHANGE frite_id frite_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ACCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ABE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_frite MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ACCD7E912');
        $this->addSql('ALTER TABLE menu_frite DROP FOREIGN KEY FK_B147E70ABE00B4D9');
        $this->addSql('ALTER TABLE menu_frite DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE menu_frite DROP id, CHANGE menu_id menu_id INT NOT NULL, CHANGE frite_id frite_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ACCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_frite ADD CONSTRAINT FK_B147E70ABE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_frite ADD PRIMARY KEY (menu_id, frite_id)');
    }
}
