<?php declare(strict_types = 1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226180252 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote__polls (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, is_over TINYINT(1) NOT NULL, is_approved TINYINT(1) NOT NULL, type VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote__common_polls (id INT NOT NULL, title VARCHAR(150) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote__options (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, value VARCHAR(100) NOT NULL, INDEX IDX_5A22B2D33C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote__votes (user_id INT NOT NULL, poll_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_D754E409A76ED395 (user_id), INDEX IDX_D754E4093C947C0F (poll_id), INDEX IDX_D754E409A7C41D6F (option_id), PRIMARY KEY(user_id, poll_id, option_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote__feature_polls (id INT NOT NULL, feedback_id VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote__common_polls ADD CONSTRAINT FK_3C882BF2BF396750 FOREIGN KEY (id) REFERENCES vote__polls (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote__options ADD CONSTRAINT FK_5A22B2D33C947C0F FOREIGN KEY (poll_id) REFERENCES vote__polls (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E409A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E4093C947C0F FOREIGN KEY (poll_id) REFERENCES vote__polls (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E409A7C41D6F FOREIGN KEY (option_id) REFERENCES vote__options (id)');
        $this->addSql('ALTER TABLE vote__feature_polls ADD CONSTRAINT FK_A9264FEBF396750 FOREIGN KEY (id) REFERENCES vote__polls (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote__common_polls DROP FOREIGN KEY FK_3C882BF2BF396750');
        $this->addSql('ALTER TABLE vote__options DROP FOREIGN KEY FK_5A22B2D33C947C0F');
        $this->addSql('ALTER TABLE vote__votes DROP FOREIGN KEY FK_D754E4093C947C0F');
        $this->addSql('ALTER TABLE vote__feature_polls DROP FOREIGN KEY FK_A9264FEBF396750');
        $this->addSql('ALTER TABLE vote__votes DROP FOREIGN KEY FK_D754E409A7C41D6F');
        $this->addSql('DROP TABLE vote__polls');
        $this->addSql('DROP TABLE vote__common_polls');
        $this->addSql('DROP TABLE vote__options');
        $this->addSql('DROP TABLE vote__votes');
        $this->addSql('DROP TABLE vote__feature_polls');
    }
}
