<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221016113008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE jobs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE jobs (id INT NOT NULL, companies_id INT DEFAULT NULL, advertisements_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email_sent_companies VARCHAR(255) NOT NULL, email_sent_apply VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A8936DC56AE4741E ON jobs (companies_id)');
        $this->addSql('CREATE INDEX IDX_A8936DC56DB58F3E ON jobs (advertisements_id)');
        $this->addSql('CREATE TABLE jobs_applicants (jobs_id INT NOT NULL, applicants_id INT NOT NULL, PRIMARY KEY(jobs_id, applicants_id))');
        $this->addSql('CREATE INDEX IDX_62B3B79348704627 ON jobs_applicants (jobs_id)');
        $this->addSql('CREATE INDEX IDX_62B3B79390E19E23 ON jobs_applicants (applicants_id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC56AE4741E FOREIGN KEY (companies_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC56DB58F3E FOREIGN KEY (advertisements_id) REFERENCES advertisements (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE jobs_applicants ADD CONSTRAINT FK_62B3B79348704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE jobs_applicants ADD CONSTRAINT FK_62B3B79390E19E23 FOREIGN KEY (applicants_id) REFERENCES applicants (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE jobs_id_seq CASCADE');
        $this->addSql('ALTER TABLE jobs DROP CONSTRAINT FK_A8936DC56AE4741E');
        $this->addSql('ALTER TABLE jobs DROP CONSTRAINT FK_A8936DC56DB58F3E');
        $this->addSql('ALTER TABLE jobs_applicants DROP CONSTRAINT FK_62B3B79348704627');
        $this->addSql('ALTER TABLE jobs_applicants DROP CONSTRAINT FK_62B3B79390E19E23');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE jobs_applicants');
    }
}
