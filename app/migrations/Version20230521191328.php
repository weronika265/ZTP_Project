<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521191328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advertisers (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) NOT NULL, phone VARCHAR(15) DEFAULT NULL, name VARCHAR(45) DEFAULT NULL, UNIQUE INDEX uq_advertisers_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advertisements ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1E12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_5C755F1E12469DE2 ON advertisements (category_id)');
        $this->addSql('CREATE UNIQUE INDEX uq_categories_name ON categories (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE advertisers');
        $this->addSql('ALTER TABLE advertisements DROP FOREIGN KEY FK_5C755F1E12469DE2');
        $this->addSql('DROP INDEX IDX_5C755F1E12469DE2 ON advertisements');
        $this->addSql('ALTER TABLE advertisements DROP category_id');
        $this->addSql('DROP INDEX uq_categories_name ON categories');
    }
}
