<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213160629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD office_id INT DEFAULT NULL, DROP office');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FFA0C224 FOREIGN KEY (office_id) REFERENCES `users` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9BD733A0 ON users (code_office)');
        $this->addSql('CREATE INDEX IDX_1483A5E9FFA0C224 ON users (office_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `users` DROP FOREIGN KEY FK_1483A5E9FFA0C224');
        $this->addSql('DROP INDEX UNIQ_1483A5E9BD733A0 ON `users`');
        $this->addSql('DROP INDEX IDX_1483A5E9FFA0C224 ON `users`');
        $this->addSql('ALTER TABLE `users` ADD office VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP office_id');
    }
}
