<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314163139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game__factions_colors (id INT AUTO_INCREMENT NOT NULL, black VARCHAR(10) NOT NULL, grey VARCHAR(10) NOT NULL, white VARCHAR(10) NOT NULL, main VARCHAR(10) NOT NULL, main_light VARCHAR(10) NOT NULL, main_lighter VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game__factions CHANGE color color INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game__factions ADD CONSTRAINT FK_17E4DCA6665648E9 FOREIGN KEY (color) REFERENCES game__factions_colors (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17E4DCA6665648E9 ON game__factions (color)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game__factions DROP FOREIGN KEY FK_17E4DCA6665648E9');
        $this->addSql('DROP TABLE game__factions_colors');
        $this->addSql('DROP INDEX UNIQ_17E4DCA6665648E9 ON game__factions');
        $this->addSql('ALTER TABLE game__factions CHANGE color color VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
