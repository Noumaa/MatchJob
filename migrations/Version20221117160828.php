<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117160828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demand (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, offer_id INT NOT NULL, note VARCHAR(255) DEFAULT NULL, INDEX IDX_428D7973A76ED395 (user_id), INDEX IDX_428D797353C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demand_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demand_status_change (id INT AUTO_INCREMENT NOT NULL, demand_id INT NOT NULL, demand_status_id INT NOT NULL, date_time DATETIME NOT NULL, INDEX IDX_896E38915D022E59 (demand_id), INDEX IDX_896E38917CBF1EEE (demand_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, label VARCHAR(255) NOT NULL, salary DOUBLE PRECISION DEFAULT NULL, description LONGTEXT NOT NULL, duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, INDEX IDX_29D6873EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demand ADD CONSTRAINT FK_428D7973A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE demand ADD CONSTRAINT FK_428D797353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE demand_status_change ADD CONSTRAINT FK_896E38915D022E59 FOREIGN KEY (demand_id) REFERENCES demand (id)');
        $this->addSql('ALTER TABLE demand_status_change ADD CONSTRAINT FK_896E38917CBF1EEE FOREIGN KEY (demand_status_id) REFERENCES demand_status (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD profesionnal_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64973D5F860 FOREIGN KEY (profesionnal_status_id) REFERENCES profesionnal_status (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64973D5F860 ON user (profesionnal_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demand DROP FOREIGN KEY FK_428D7973A76ED395');
        $this->addSql('ALTER TABLE demand DROP FOREIGN KEY FK_428D797353C674EE');
        $this->addSql('ALTER TABLE demand_status_change DROP FOREIGN KEY FK_896E38915D022E59');
        $this->addSql('ALTER TABLE demand_status_change DROP FOREIGN KEY FK_896E38917CBF1EEE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EA76ED395');
        $this->addSql('DROP TABLE demand');
        $this->addSql('DROP TABLE demand_status');
        $this->addSql('DROP TABLE demand_status_change');
        $this->addSql('DROP TABLE offer');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64973D5F860');
        $this->addSql('DROP INDEX IDX_8D93D64973D5F860 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP profesionnal_status_id');
    }
}
