<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203183538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demand_status_change (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, demand_id INTEGER NOT NULL, demand_status_id INTEGER NOT NULL, date_add DATETIME NOT NULL, date_update DATETIME NOT NULL, CONSTRAINT FK_896E38915D022E59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_896E38917CBF1EEE FOREIGN KEY (demand_status_id) REFERENCES demand_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_896E38915D022E59 ON demand_status_change (demand_id)');
        $this->addSql('CREATE INDEX IDX_896E38917CBF1EEE ON demand_status_change (demand_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE demand_status_change');
    }
}
