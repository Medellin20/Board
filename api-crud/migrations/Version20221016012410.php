<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221016012410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE applicants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE applicants (id INT NOT NULL, ad_id INT DEFAULT NULL, name_ap VARCHAR(255) NOT NULL, firstname_ap VARCHAR(255) NOT NULL, email_ap VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7FAFCADB4F34D596 ON applicants (ad_id)');
        $this->addSql('ALTER TABLE applicants ADD CONSTRAINT FK_7FAFCADB4F34D596 FOREIGN KEY (ad_id) REFERENCES advertisements (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE applicants_id_seq CASCADE');
        $this->addSql('ALTER TABLE applicants DROP CONSTRAINT FK_7FAFCADB4F34D596');
        $this->addSql('DROP TABLE applicants');
    }
}
