<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203203911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__course AS SELECT id, resume_id, label, location, started_at, ended_at FROM course');
        $this->addSql('DROP TABLE course');
        $this->addSql('CREATE TABLE course (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, started_at DATE NOT NULL, ended_at DATE DEFAULT NULL, CONSTRAINT FK_169E6FB9D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course (id, resume_id, label, location, started_at, ended_at) SELECT id, resume_id, label, location, started_at, ended_at FROM __temp__course');
        $this->addSql('DROP TABLE __temp__course');
        $this->addSql('CREATE INDEX IDX_169E6FB9D262AF09 ON course (resume_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__course AS SELECT id, resume_id, label, location, started_at, ended_at FROM course');
        $this->addSql('DROP TABLE course');
        $this->addSql('CREATE TABLE course (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, started_at DATE DEFAULT NULL --(DC2Type:date_immutable)
        , ended_at DATE DEFAULT NULL --(DC2Type:date_immutable)
        , CONSTRAINT FK_169E6FB9D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO course (id, resume_id, label, location, started_at, ended_at) SELECT id, resume_id, label, location, started_at, ended_at FROM __temp__course');
        $this->addSql('DROP TABLE __temp__course');
        $this->addSql('CREATE INDEX IDX_169E6FB9D262AF09 ON course (resume_id)');
    }
}
