<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131195015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demands (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_individual_id INTEGER DEFAULT NULL, id_offer_id INTEGER NOT NULL, date_add DATETIME NOT NULL, date_update DATETIME NOT NULL, CONSTRAINT FK_D24062F4100FCD68 FOREIGN KEY (id_individual_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D24062F431D987B FOREIGN KEY (id_offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D24062F4100FCD68 ON demands (id_individual_id)');
        $this->addSql('CREATE INDEX IDX_D24062F431D987B ON demands (id_offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demands');
    }
}
