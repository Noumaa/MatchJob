<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505172453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD address VARCHAR(255) NOT NULL, ADD zip_code VARCHAR(5) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD region VARCHAR(255) NOT NULL, ADD department VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL, ADD profile_picture VARCHAR(255) DEFAULT NULL, ADD banner_picture VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD name VARCHAR(255) DEFAULT NULL, ADD siret VARCHAR(255) DEFAULT NULL, ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD date_of_birth DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP address, DROP zip_code, DROP city, DROP country, DROP region, DROP department, DROP phone, DROP profile_picture, DROP banner_picture, DROP created_at, DROP name, DROP siret, DROP first_name, DROP last_name, DROP date_of_birth, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
