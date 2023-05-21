<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521203609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisements CHANGE advertiser_id advertiser_id INT NOT NULL');
        $this->addSql('DROP INDEX uq_advertisers_email ON advertisers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX uq_advertisers_email ON advertisers (email)');
        $this->addSql('ALTER TABLE advertisements CHANGE advertiser_id advertiser_id INT DEFAULT NULL');
    }
}
