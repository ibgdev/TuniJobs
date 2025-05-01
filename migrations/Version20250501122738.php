<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501122738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, cv VARCHAR(255) NOT NULL, letter_motivation VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC13481D195 (job_offer_id), INDEX IDX_A45BDDC1FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, responsable_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, matricule_fiscale VARCHAR(255) NOT NULL, secteur VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, siteweb VARCHAR(255) NOT NULL, telephone INT NOT NULL, logo_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F53C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_publication DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', location VARCHAR(255) NOT NULL, INDEX IDX_288A3A4EA4AEAFEA (entreprise_id), INDEX IDX_288A3A4EBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, note INT NOT NULL, commentaire VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_794381C6A4AEAFEA (entreprise_id), INDEX IDX_794381C6FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC13481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F53C59D72 FOREIGN KEY (responsable_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC13481D195');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1FB88E14F');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F53C59D72');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EA4AEAFEA');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EBCF5E72D');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A4AEAFEA');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6FB88E14F');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
