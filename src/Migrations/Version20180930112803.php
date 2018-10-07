<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180930112803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scrumban__user_stories (id VARCHAR(15) NOT NULL, sprint_id INT DEFAULT NULL, epic_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, value INT NOT NULL, estimated_time INT NOT NULL, spent_time INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4A4C76FA8C24077B (sprint_id), INDEX IDX_4A4C76FA6B71E00E (epic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scrumban__sprints (id INT AUTO_INCREMENT NOT NULL, demo_url VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scrumban__versions (id VARCHAR(15) NOT NULL, planned_at DATETIME NOT NULL, released_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scrumban__epics (id INT NOT NULL, title VARCHAR(255) NOT NULL, estimated_time INT NOT NULL, spent_time INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scrumban__user_stories ADD CONSTRAINT FK_4A4C76FA8C24077B FOREIGN KEY (sprint_id) REFERENCES scrumban__sprints (id)');
        $this->addSql('ALTER TABLE scrumban__user_stories ADD CONSTRAINT FK_4A4C76FA6B71E00E FOREIGN KEY (epic_id) REFERENCES scrumban__epics (id)');
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFD1844E6B7');
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFDA76ED395');
        $this->addSql('ALTER TABLE game__players DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFD1844E6B7 FOREIGN KEY (server_id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFDA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__players ADD PRIMARY KEY (server_id, user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scrumban__user_stories DROP FOREIGN KEY FK_4A4C76FA8C24077B');
        $this->addSql('ALTER TABLE scrumban__user_stories DROP FOREIGN KEY FK_4A4C76FA6B71E00E');
        $this->addSql('DROP TABLE scrumban__user_stories');
        $this->addSql('DROP TABLE scrumban__sprints');
        $this->addSql('DROP TABLE scrumban__versions');
        $this->addSql('DROP TABLE scrumban__epics');
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFD1844E6B7');
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFDA76ED395');
        $this->addSql('ALTER TABLE game__players DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFD1844E6B7 FOREIGN KEY (server_id) REFERENCES game__servers (id)');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFDA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__players ADD PRIMARY KEY (user_id, server_id)');
    }
}
