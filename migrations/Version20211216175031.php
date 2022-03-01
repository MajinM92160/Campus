<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216175031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tickets');
        $this->addSql('ALTER TABLE participations DROP INDEX UNIQ_FDC6C6E871F7E88B, ADD INDEX IDX_FDC6C6E871F7E88B (event_id)');
        $this->addSql('ALTER TABLE participations CHANGE event_id event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD token VARCHAR(255) DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participations DROP INDEX IDX_FDC6C6E871F7E88B, ADD UNIQUE INDEX UNIQ_FDC6C6E871F7E88B (event_id)');
        $this->addSql('ALTER TABLE participations CHANGE event_id event_id INT NOT NULL');
        $this->addSql('ALTER TABLE `users` DROP token, CHANGE first_name first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
