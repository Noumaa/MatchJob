<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202192257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demands (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, individual_id INTEGER DEFAULT NULL, offer_id INTEGER NOT NULL, date_add DATETIME NOT NULL, date_update DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT FK_D24062F4AE271C0D FOREIGN KEY (individual_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D24062F453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D24062F4AE271C0D ON demands (individual_id)');
        $this->addSql('CREATE INDEX IDX_D24062F453C674EE ON demands (offer_id)');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, label VARCHAR(255) NOT NULL, money_per_hour DOUBLE PRECISION DEFAULT NULL, description CLOB NOT NULL, duration VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
        , start_at DATE DEFAULT NULL, end_at DATE DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_archived BOOLEAN NOT NULL, CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_29D6873EA76ED395 ON offer (user_id)');
        $this->addSql('CREATE TABLE resume (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE resume_resume_category (resume_id INTEGER NOT NULL, resume_category_id INTEGER NOT NULL, PRIMARY KEY(resume_id, resume_category_id), CONSTRAINT FK_F9A75E3D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F9A75E3F04C9784 FOREIGN KEY (resume_category_id) REFERENCES resume_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F9A75E3D262AF09 ON resume_resume_category (resume_id)');
        $this->addSql('CREATE INDEX IDX_F9A75E3F04C9784 ON resume_resume_category (resume_category_id)');
        $this->addSql('CREATE TABLE resume_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE resume_category_resume_item (resume_category_id INTEGER NOT NULL, resume_item_id INTEGER NOT NULL, PRIMARY KEY(resume_category_id, resume_item_id), CONSTRAINT FK_1703A790F04C9784 FOREIGN KEY (resume_category_id) REFERENCES resume_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1703A790242FEDB6 FOREIGN KEY (resume_item_id) REFERENCES resume_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1703A790F04C9784 ON resume_category_resume_item (resume_category_id)');
        $this->addSql('CREATE INDEX IDX_1703A790242FEDB6 ON resume_category_resume_item (resume_item_id)');
        $this->addSql('CREATE TABLE resume_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, resume_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_verified BOOLEAN NOT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, CONSTRAINT FK_8D93D649D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
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
        $this->addSql('DROP TABLE demands');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP TABLE resume_resume_category');
        $this->addSql('DROP TABLE resume_category');
        $this->addSql('DROP TABLE resume_category_resume_item');
        $this->addSql('DROP TABLE resume_item');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
