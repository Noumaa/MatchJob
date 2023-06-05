<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605092017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE course (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, started_at DATE NOT NULL, ended_at DATE DEFAULT NULL, CONSTRAINT FK_169E6FB9D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_169E6FB9D262AF09 ON course (resume_id)');
        $this->addSql('CREATE TABLE demand_status (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE demand_status_change (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, demand_id INTEGER NOT NULL, demand_status_id INTEGER NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_896E38915D022E59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_896E38917CBF1EEE FOREIGN KEY (demand_status_id) REFERENCES demand_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_896E38915D022E59 ON demand_status_change (demand_id)');
        $this->addSql('CREATE INDEX IDX_896E38917CBF1EEE ON demand_status_change (demand_status_id)');
        $this->addSql('CREATE TABLE demands (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, applicant_id INTEGER DEFAULT NULL, offer_id INTEGER NOT NULL, status_id INTEGER NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT FK_D24062F497139001 FOREIGN KEY (applicant_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D24062F453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D24062F46BF700BD FOREIGN KEY (status_id) REFERENCES demand_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D24062F497139001 ON demands (applicant_id)');
        $this->addSql('CREATE INDEX IDX_D24062F453C674EE ON demands (offer_id)');
        $this->addSql('CREATE INDEX IDX_D24062F46BF700BD ON demands (status_id)');
        $this->addSql('CREATE TABLE experience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, business_id INTEGER DEFAULT NULL, resume_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, started_at DATE NOT NULL, ended_at DATE DEFAULT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_590C103A89DB457 FOREIGN KEY (business_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_590C103D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_590C103A89DB457 ON experience (business_id)');
        $this->addSql('CREATE INDEX IDX_590C103D262AF09 ON experience (resume_id)');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, demand_id INTEGER DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, sended_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , read_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , type INTEGER NOT NULL, CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CA5D022E59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA5D022E59 ON notification (demand_id)');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, category_id INTEGER DEFAULT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , start_at DATE DEFAULT NULL, end_at DATE DEFAULT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_archived BOOLEAN NOT NULL, views INTEGER NOT NULL, CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E12469DE2 ON offer (category_id)');
        $this->addSql('CREATE TABLE resume (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, CONSTRAINT FK_5E3DE477D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5E3DE477D262AF09 ON skill (resume_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, banner_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , public_description VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, CONSTRAINT FK_8D93D649D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D262AF09 ON user (resume_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE demand_status');
        $this->addSql('DROP TABLE demand_status_change');
        $this->addSql('DROP TABLE demands');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
