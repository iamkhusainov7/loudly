<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113185045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, invited_user_id INT NOT NULL, invited_by_id INT NOT NULL, invited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_canceled TINYINT(1) NOT NULL, is_accepted TINYINT(1) NOT NULL, is_declined TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F11D61A2C58DAD6E (invited_user_id), INDEX IDX_F11D61A2A7B4A7E3 (invited_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2C58DAD6E FOREIGN KEY (invited_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2A7B4A7E3 FOREIGN KEY (invited_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE invitation');
    }
}
