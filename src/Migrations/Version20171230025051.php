<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171230025051 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game__factions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game__server_factions (server_id INT NOT NULL, faction_id INT NOT NULL, INDEX IDX_6AEDF9881844E6B7 (server_id), INDEX IDX_6AEDF9884448F8DA (faction_id), PRIMARY KEY(server_id, faction_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game__server_factions ADD CONSTRAINT FK_6AEDF9881844E6B7 FOREIGN KEY (server_id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__server_factions ADD CONSTRAINT FK_6AEDF9884448F8DA FOREIGN KEY (faction_id) REFERENCES game__factions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game__server_factions DROP FOREIGN KEY FK_6AEDF9884448F8DA');
        $this->addSql('DROP TABLE game__factions');
        $this->addSql('DROP TABLE game__server_factions');
    }
}
