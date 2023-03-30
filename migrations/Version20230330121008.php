<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330121008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demands DROP FOREIGN KEY FK_D24062F4AE271C0D');
        $this->addSql('DROP INDEX IDX_D24062F4AE271C0D ON demands');
        $this->addSql('ALTER TABLE demands CHANGE individual_id applicant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F497139001 FOREIGN KEY (applicant_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D24062F497139001 ON demands (applicant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demands DROP FOREIGN KEY FK_D24062F497139001');
        $this->addSql('DROP INDEX IDX_D24062F497139001 ON demands');
        $this->addSql('ALTER TABLE demands CHANGE applicant_id individual_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F4AE271C0D FOREIGN KEY (individual_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D24062F4AE271C0D ON demands (individual_id)');
    }
}
