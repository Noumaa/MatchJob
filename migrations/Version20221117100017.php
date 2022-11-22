<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117100017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profesionnal_status CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD date_of_birth DATE DEFAULT NULL, ADD cv VARCHAR(255) DEFAULT NULL, ADD adress VARCHAR(255) NOT NULL, ADD zip_code INT NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL, ADD siret VARCHAR(255) DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profesionnal_status CHANGE id id INT NOT NULL, CHANGE name name VARCHAR(84) NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP first_name, DROP last_name, DROP date_of_birth, DROP cv, DROP adress, DROP zip_code, DROP city, DROP country, DROP phone, DROP siret, DROP name');
    }
}
