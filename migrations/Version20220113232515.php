<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113232515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation ADD status VARCHAR(255) NOT NULL, DROP is_canceled, DROP is_accepted, DROP is_declined');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invitation ADD is_canceled TINYINT(1) NOT NULL, ADD is_accepted TINYINT(1) NOT NULL, ADD is_declined TINYINT(1) NOT NULL, DROP status');
    }
}
