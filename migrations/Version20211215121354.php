<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215121354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP INDEX UNIQ_5387574AA76ED395, ADD INDEX IDX_5387574AA76ED395 (user_id)');
        $this->addSql('ALTER TABLE participations DROP INDEX UNIQ_FDC6C6E871F7E88B, ADD INDEX IDX_FDC6C6E871F7E88B (event_id)');
        $this->addSql('ALTER TABLE participations CHANGE event_id event_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_1483A5E9BD733A0 ON users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP INDEX IDX_5387574AA76ED395, ADD UNIQUE INDEX UNIQ_5387574AA76ED395 (user_id)');
        $this->addSql('ALTER TABLE participations DROP INDEX IDX_FDC6C6E871F7E88B, ADD UNIQUE INDEX UNIQ_FDC6C6E871F7E88B (event_id)');
        $this->addSql('ALTER TABLE participations CHANGE event_id event_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9BD733A0 ON `users` (code_office)');
    }
}
