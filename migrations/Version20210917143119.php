<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917143119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, actor_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_47CC8C9210DAF24A (actor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_prospect (action_id INT NOT NULL, prospect_id INT NOT NULL, INDEX IDX_1E19FC1B9D32F035 (action_id), INDEX IDX_1E19FC1BD182060A (prospect_id), PRIMARY KEY(action_id, prospect_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, reporter_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, project LONGTEXT DEFAULT NULL, is_visible TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C9CE8C7DE1CFE6F5 (reporter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9210DAF24A FOREIGN KEY (actor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE action_prospect ADD CONSTRAINT FK_1E19FC1B9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_prospect ADD CONSTRAINT FK_1E19FC1BD182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prospect ADD CONSTRAINT FK_C9CE8C7DE1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action_prospect DROP FOREIGN KEY FK_1E19FC1B9D32F035');
        $this->addSql('ALTER TABLE action_prospect DROP FOREIGN KEY FK_1E19FC1BD182060A');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9210DAF24A');
        $this->addSql('ALTER TABLE prospect DROP FOREIGN KEY FK_C9CE8C7DE1CFE6F5');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE action_prospect');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE user');
    }
}
