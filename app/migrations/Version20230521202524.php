<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521202524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisements ADD advertiser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1EBA2FCBC2 FOREIGN KEY (advertiser_id) REFERENCES advertisers (id)');
        $this->addSql('CREATE INDEX IDX_5C755F1EBA2FCBC2 ON advertisements (advertiser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisements DROP FOREIGN KEY FK_5C755F1EBA2FCBC2');
        $this->addSql('DROP INDEX IDX_5C755F1EBA2FCBC2 ON advertisements');
        $this->addSql('ALTER TABLE advertisements DROP advertiser_id');
    }
}
