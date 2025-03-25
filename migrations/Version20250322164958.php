<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250322164958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F873CC942');
        $this->addSql('DROP INDEX IDX_4FBF094F873CC942 ON company');
        $this->addSql('ALTER TABLE company DROP company_user_id, DROP statu_validation, CHANGE siteweb siteweb VARCHAR(255) NOT NULL, CHANGE telephone telephone INT NOT NULL, CHANGE matricule_fiscle matricule_fiscale VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company_user DROP nom, CHANGE company_id company_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_user ADD nom VARCHAR(255) NOT NULL, CHANGE company_id company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD company_user_id INT DEFAULT NULL, ADD statu_validation TINYINT(1) NOT NULL, CHANGE siteweb siteweb VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE matricule_fiscale matricule_fiscle VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F873CC942 FOREIGN KEY (company_user_id) REFERENCES company_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4FBF094F873CC942 ON company (company_user_id)');
    }
}
