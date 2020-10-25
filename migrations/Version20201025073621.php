<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025073621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFDA76ED395');
        $this->addSql('ALTER TABLE game__solo_servers DROP FOREIGN KEY FK_D73683F7E3C61F9');
        $this->addSql('ALTER TABLE game__tutorial_servers DROP FOREIGN KEY FK_8CF3BA2E7E3C61F9');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE vote__votes DROP FOREIGN KEY FK_D754E409A76ED395');
        $this->addSql('ALTER TABLE game__server_factions DROP FOREIGN KEY FK_6AEDF9884448F8DA');
        $this->addSql('ALTER TABLE game__factions DROP FOREIGN KEY FK_17E4DCA6665648E9');
        $this->addSql('ALTER TABLE game__servers DROP FOREIGN KEY FK_9086EBACF6B75B26');
        $this->addSql('ALTER TABLE game__multiplayer_servers DROP FOREIGN KEY FK_EDF5A852BF396750');
        $this->addSql('ALTER TABLE game__players DROP FOREIGN KEY FK_F9425DFD1844E6B7');
        $this->addSql('ALTER TABLE game__server_factions DROP FOREIGN KEY FK_6AEDF9881844E6B7');
        $this->addSql('ALTER TABLE game__solo_servers DROP FOREIGN KEY FK_D73683FBF396750');
        $this->addSql('ALTER TABLE game__tutorial_servers DROP FOREIGN KEY FK_8CF3BA2EBF396750');
        $this->addSql('ALTER TABLE scrumban__user_stories DROP FOREIGN KEY FK_4AE355F06B71E00E');
        $this->addSql('ALTER TABLE scrumban__user_stories DROP FOREIGN KEY FK_4AE355F08C24077B');
        $this->addSql('ALTER TABLE vote__polls DROP FOREIGN KEY FK_9BE35828B9C07FFC');
        $this->addSql('ALTER TABLE vote__votes DROP FOREIGN KEY FK_D754E409A7C41D6F');
        $this->addSql('ALTER TABLE vote__common_polls DROP FOREIGN KEY FK_D1B221C9BF396750');
        $this->addSql('ALTER TABLE vote__feature_polls DROP FOREIGN KEY FK_A9264FEBF396750');
        $this->addSql('ALTER TABLE vote__options DROP FOREIGN KEY FK_5A22B2D33C947C0F');
        $this->addSql('ALTER TABLE vote__votes DROP FOREIGN KEY FK_D754E4093C947C0F');
        $this->addSql('CREATE TABLE user__users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, activation_token VARCHAR(150) DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FBE95248F85E0677 (username), UNIQUE INDEX UNIQ_FBE95248E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE game__factions');
        $this->addSql('DROP TABLE game__factions_colors');
        $this->addSql('DROP TABLE game__machines');
        $this->addSql('DROP TABLE game__multiplayer_servers');
        $this->addSql('DROP TABLE game__players');
        $this->addSql('DROP TABLE game__server_factions');
        $this->addSql('DROP TABLE game__servers');
        $this->addSql('DROP TABLE game__solo_servers');
        $this->addSql('DROP TABLE game__tutorial_servers');
        $this->addSql('DROP TABLE scrumban__epics');
        $this->addSql('DROP TABLE scrumban__sprints');
        $this->addSql('DROP TABLE scrumban__user_stories');
        $this->addSql('DROP TABLE scrumban__versions');
        $this->addSql('DROP TABLE vote__common_polls');
        $this->addSql('DROP TABLE vote__feature_polls');
        $this->addSql('DROP TABLE vote__options');
        $this->addSql('DROP TABLE vote__polls');
        $this->addSql('DROP TABLE vote__votes');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email_canonical VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__factions (id INT AUTO_INCREMENT NOT NULL, color INT DEFAULT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, banner VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_17E4DCA6665648E9 (color), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__factions_colors (id INT AUTO_INCREMENT NOT NULL, black VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, grey VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, white VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, main VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, main_light VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, main_lighter VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__machines (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, host VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, public_key LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_local TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_DA8098F989D9B62 (slug), UNIQUE INDEX UNIQ_DA8098F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__multiplayer_servers (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__players (server_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F9425DFD1844E6B7 (server_id), INDEX IDX_F9425DFDA76ED395 (user_id), PRIMARY KEY(server_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__server_factions (server_id INT NOT NULL, faction_id INT NOT NULL, INDEX IDX_6AEDF9881844E6B7 (server_id), INDEX IDX_6AEDF9884448F8DA (faction_id), PRIMARY KEY(server_id, faction_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__servers (id INT AUTO_INCREMENT NOT NULL, machine_id INT DEFAULT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, banner VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, game_id INT NOT NULL, signature VARCHAR(85) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sub_domain VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, started_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9086EBACF6B75B26 (machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__solo_servers (id INT NOT NULL, owner_id INT DEFAULT NULL, INDEX IDX_D73683F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game__tutorial_servers (id INT NOT NULL, owner_id INT DEFAULT NULL, INDEX IDX_8CF3BA2E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scrumban__epics (id INT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, estimated_time INT NOT NULL, spent_time INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scrumban__sprints (id INT AUTO_INCREMENT NOT NULL, demo_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, begin_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scrumban__user_stories (id VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sprint_id INT DEFAULT NULL, epic_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, value INT NOT NULL, status VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, estimated_time DOUBLE PRECISION NOT NULL, spent_time DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4AE355F06B71E00E (epic_id), INDEX IDX_4AE355F08C24077B (sprint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE scrumban__versions (id VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, planned_at DATETIME NOT NULL, released_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote__common_polls (id INT NOT NULL, title VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote__feature_polls (id INT NOT NULL, feedback_id VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote__options (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, value VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5A22B2D33C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote__polls (id INT AUTO_INCREMENT NOT NULL, winning_option_id INT DEFAULT NULL, created_at DATETIME NOT NULL, ended_at DATETIME NOT NULL, is_over TINYINT(1) NOT NULL, score INT DEFAULT NULL, nb_votes INT DEFAULT NULL, type VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_9BE35828B9C07FFC (winning_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote__votes (user_id INT NOT NULL, poll_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_D754E4093C947C0F (poll_id), INDEX IDX_D754E409A76ED395 (user_id), INDEX IDX_D754E409A7C41D6F (option_id), PRIMARY KEY(user_id, poll_id, option_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE game__factions ADD CONSTRAINT FK_17E4DCA6665648E9 FOREIGN KEY (color) REFERENCES game__factions_colors (id)');
        $this->addSql('ALTER TABLE game__multiplayer_servers ADD CONSTRAINT FK_EDF5A852BF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFD1844E6B7 FOREIGN KEY (server_id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__players ADD CONSTRAINT FK_F9425DFDA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__server_factions ADD CONSTRAINT FK_6AEDF9881844E6B7 FOREIGN KEY (server_id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__server_factions ADD CONSTRAINT FK_6AEDF9884448F8DA FOREIGN KEY (faction_id) REFERENCES game__factions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__servers ADD CONSTRAINT FK_9086EBACF6B75B26 FOREIGN KEY (machine_id) REFERENCES game__machines (id)');
        $this->addSql('ALTER TABLE game__solo_servers ADD CONSTRAINT FK_D73683F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__solo_servers ADD CONSTRAINT FK_D73683FBF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game__tutorial_servers ADD CONSTRAINT FK_8CF3BA2E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE game__tutorial_servers ADD CONSTRAINT FK_8CF3BA2EBF396750 FOREIGN KEY (id) REFERENCES game__servers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE scrumban__user_stories ADD CONSTRAINT FK_4AE355F06B71E00E FOREIGN KEY (epic_id) REFERENCES scrumban__epics (id)');
        $this->addSql('ALTER TABLE scrumban__user_stories ADD CONSTRAINT FK_4AE355F08C24077B FOREIGN KEY (sprint_id) REFERENCES scrumban__sprints (id)');
        $this->addSql('ALTER TABLE vote__common_polls ADD CONSTRAINT FK_D1B221C9BF396750 FOREIGN KEY (id) REFERENCES vote__polls (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote__feature_polls ADD CONSTRAINT FK_A9264FEBF396750 FOREIGN KEY (id) REFERENCES vote__polls (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote__options ADD CONSTRAINT FK_5A22B2D33C947C0F FOREIGN KEY (poll_id) REFERENCES vote__polls (id)');
        $this->addSql('ALTER TABLE vote__polls ADD CONSTRAINT FK_9BE35828B9C07FFC FOREIGN KEY (winning_option_id) REFERENCES vote__options (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E4093C947C0F FOREIGN KEY (poll_id) REFERENCES vote__polls (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E409A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE vote__votes ADD CONSTRAINT FK_D754E409A7C41D6F FOREIGN KEY (option_id) REFERENCES vote__options (id)');
        $this->addSql('DROP TABLE user__users');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
    }
}
