<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330122728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demands ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F46BF700BD FOREIGN KEY (status_id) REFERENCES demand_status (id)');
        $this->addSql('CREATE INDEX IDX_D24062F46BF700BD ON demands (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demands DROP FOREIGN KEY FK_D24062F46BF700BD');
        $this->addSql('DROP INDEX IDX_D24062F46BF700BD ON demands');
        $this->addSql('ALTER TABLE demands DROP status_id');
    }
}
