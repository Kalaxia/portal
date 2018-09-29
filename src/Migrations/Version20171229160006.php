<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171229160006 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote__polls ADD winning_option_id INT DEFAULT NULL, ADD score INT DEFAULT NULL, ADD nb_votes INT DEFAULT NULL, DROP is_approved');
        $this->addSql('ALTER TABLE vote__polls ADD CONSTRAINT FK_9BE35828B9C07FFC FOREIGN KEY (winning_option_id) REFERENCES vote__options (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BE35828B9C07FFC ON vote__polls (winning_option_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote__polls DROP FOREIGN KEY FK_9BE35828B9C07FFC');
        $this->addSql('DROP INDEX UNIQ_9BE35828B9C07FFC ON vote__polls');
        $this->addSql('ALTER TABLE vote__polls ADD is_approved TINYINT(1) NOT NULL, DROP winning_option_id, DROP score, DROP nb_votes');
    }
}
