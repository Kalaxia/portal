<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181004124308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scrumban__user_stories ADD status VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE scrumban__user_stories RENAME INDEX idx_4a4c76fa8c24077b TO IDX_4AE355F08C24077B');
        $this->addSql('ALTER TABLE scrumban__user_stories RENAME INDEX idx_4a4c76fa6b71e00e TO IDX_4AE355F06B71E00E');
        $this->addSql('ALTER TABLE scrumban__sprints CHANGE demo_url demo_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE scrumban__epics ADD status VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scrumban__epics DROP status');
        $this->addSql('ALTER TABLE scrumban__sprints CHANGE demo_url demo_url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE scrumban__user_stories DROP status');
        $this->addSql('ALTER TABLE scrumban__user_stories RENAME INDEX idx_4ae355f08c24077b TO IDX_4A4C76FA8C24077B');
        $this->addSql('ALTER TABLE scrumban__user_stories RENAME INDEX idx_4ae355f06b71e00e TO IDX_4A4C76FA6B71E00E');
    }
}
