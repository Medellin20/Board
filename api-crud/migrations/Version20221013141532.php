<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013141532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE advertisements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisements (id INT NOT NULL, companies_id INT NOT NULL, name_ad VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C755F1E6AE4741E ON advertisements (companies_id)');
        $this->addSql('ALTER TABLE advertisements ADD CONSTRAINT FK_5C755F1E6AE4741E FOREIGN KEY (companies_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE advertisements_id_seq CASCADE');
        $this->addSql('ALTER TABLE advertisements DROP CONSTRAINT FK_5C755F1E6AE4741E');
        $this->addSql('DROP TABLE advertisements');
    }
}
