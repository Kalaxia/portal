<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171122224007 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game__servers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, description LONGTEXT NOT NULL, banner VARCHAR(255) NOT NULL, public_key LONGTEXT NOT NULL, created_at DATETIME NOT NULL, started_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game__solo_servers (id INT NOT NULL, owner_id INT DEFAULT NULL, INDEX IDX_D73683F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game__tutorial_servers (id INT NOT NULL, owner_id INT DEFAULT NULL, INDEX IDX_8CF3BA2E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game__multiplayer_servers (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game__solo_servers ADD CONSTRAINT FK_D73683F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__solo_servers ADD CONSTRAINT FK_D73683FBF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__tutorial_servers ADD CONSTRAINT FK_8CF3BA2E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__tutorial_servers ADD CONSTRAINT FK_8CF3BA2EBF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__multiplayer_servers ADD CONSTRAINT FK_EDF5A852BF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game__solo_servers DROP FOREIGN KEY FK_D73683FBF396750');
        $this->addSql('ALTER TABLE game__tutorial_servers DROP FOREIGN KEY FK_8CF3BA2EBF396750');
        $this->addSql('ALTER TABLE game__multiplayer_servers DROP FOREIGN KEY FK_EDF5A852BF396750');
        $this->addSql('DROP TABLE game__servers');
        $this->addSql('DROP TABLE game__solo_servers');
        $this->addSql('DROP TABLE game__tutorial_servers');
        $this->addSql('DROP TABLE game__multiplayer_servers');
    }
}
