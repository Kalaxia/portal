<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180507182020 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game__machines (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(80) NOT NULL, host VARCHAR(80) NOT NULL, public_key LONGTEXT NOT NULL, is_local TINYINT(1) NOT NULL DEFAULT 0, UNIQUE INDEX UNIQ_DA8098F5E237E06 (name), UNIQUE INDEX UNIQ_DA8098F989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO game__machines (name, slug, host, public_key) SELECT name, name, host, public_key FROM game__servers');
        $this->addSql('ALTER TABLE game__servers ADD machine_id INT DEFAULT NULL, DROP host, DROP public_key');
        $this->addSql('ALTER TABLE game__servers ADD CONSTRAINT FK_9086EBACF6B75B26 FOREIGN KEY (machine_id) REFERENCES game__machines (id)');
        $this->addSql('CREATE INDEX IDX_9086EBACF6B75B26 ON game__servers (machine_id)');
        $this->addSql('UPDATE game__servers SET machine_id = id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game__servers DROP FOREIGN KEY FK_9086EBACF6B75B26');
        $this->addSql('DROP TABLE game__machines');
        $this->addSql('DROP INDEX IDX_9086EBACF6B75B26 ON game__servers');
        $this->addSql('ALTER TABLE game__servers ADD host VARCHAR(80) NOT NULL COLLATE utf8_unicode_ci, ADD public_key LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP machine_id');
    }
}
