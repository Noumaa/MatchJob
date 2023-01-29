<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129152038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , start_at DATE DEFAULT NULL, end_at DATE DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_archived BOOLEAN NOT NULL, CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO offer (id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at) SELECT id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, address, zip_code, city, country, phone, name, siret, first_name, last_name, date_of_birth, is_verified FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, address, zip_code, city, country, phone, name, siret, first_name, last_name, date_of_birth, is_verified) SELECT id, email, roles, password, address, zip_code, city, country, phone, name, siret, first_name, last_name, date_of_birth, is_verified FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , start_at DATE DEFAULT NULL, end_at DATE DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO offer (id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at) SELECT id, user_id, label, money_per_hour, description, duration, start_at, end_at, created_at FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, address, zip_code, city, country, phone, is_verified, name, siret, first_name, last_name, date_of_birth FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, address, zip_code, city, country, phone, is_verified, name, siret, first_name, last_name, date_of_birth) SELECT id, email, roles, password, address, zip_code, city, country, phone, is_verified, name, siret, first_name, last_name, date_of_birth FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
