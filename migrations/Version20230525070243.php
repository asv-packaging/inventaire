<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525070243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecran (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT DEFAULT NULL, statut_id INT NOT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_3FFAFD4AFB88E14F (utilisateur_id), INDEX IDX_3FFAFD4AC4598A51 (emplacement_id), INDEX IDX_3FFAFD4AF6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pc_fixe (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, statut_id INT NOT NULL, stockage_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, os VARCHAR(255) DEFAULT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_C65214E2FB88E14F (utilisateur_id), INDEX IDX_C65214E2C4598A51 (emplacement_id), INDEX IDX_C65214E2F6203804 (statut_id), INDEX IDX_C65214E2DAA83D7F (stockage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pc_portable (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, statut_id INT NOT NULL, stockage_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, os VARCHAR(255) DEFAULT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_A5D1ABC6FB88E14F (utilisateur_id), INDEX IDX_A5D1ABC6C4598A51 (emplacement_id), INDEX IDX_A5D1ABC6F6203804 (statut_id), INDEX IDX_A5D1ABC6DAA83D7F (stockage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serveur (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, statut_id INT NOT NULL, stockage_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, os VARCHAR(255) DEFAULT NULL, physique TINYINT(1) NOT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_77CC53A6C4598A51 (emplacement_id), INDEX IDX_77CC53A6F6203804 (statut_id), INDEX IDX_77CC53A6DAA83D7F (stockage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stockage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tablette (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, statut_id INT NOT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_508CDDD7FB88E14F (utilisateur_id), INDEX IDX_508CDDD7C4598A51 (emplacement_id), INDEX IDX_508CDDD7F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telephone (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, statut_id INT NOT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, imei1 VARCHAR(255) DEFAULT NULL, imei2 VARCHAR(255) DEFAULT NULL, date_achat INT DEFAULT NULL, date_garantie INT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_450FF010FB88E14F (utilisateur_id), INDEX IDX_450FF010F6203804 (statut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AF6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id)');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id)');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF010FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF010F6203804 FOREIGN KEY (statut_id) REFERENCES statut (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AFB88E14F');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AC4598A51');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AF6203804');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2FB88E14F');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2C4598A51');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2F6203804');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2DAA83D7F');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6FB88E14F');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6C4598A51');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6F6203804');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6DAA83D7F');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6C4598A51');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6F6203804');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6DAA83D7F');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7FB88E14F');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7C4598A51');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7F6203804');
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF010FB88E14F');
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF010F6203804');
        $this->addSql('DROP TABLE ecran');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE pc_fixe');
        $this->addSql('DROP TABLE pc_portable');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE stockage');
        $this->addSql('DROP TABLE tablette');
        $this->addSql('DROP TABLE telephone');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE utilisateur');
    }
}
