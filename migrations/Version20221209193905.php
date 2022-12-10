<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209193905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, user_id, label, money_per_hour, description, duration, created_at FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , start_at DATE DEFAULT NULL, end_at DATE DEFAULT NULL, CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO offer (id, user_id, label, money_per_hour, description, duration, created_at) SELECT id, user_id, label, money_per_hour, description, duration, created_at FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, user_id, label, money_per_hour, description, duration, created_at FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO offer (id, user_id, label, money_per_hour, description, duration, created_at) SELECT id, user_id, label, money_per_hour, description, duration, created_at FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
    }
}
