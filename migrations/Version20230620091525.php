<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620091525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ecran (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_3FFAFD4AFB88E14F (utilisateur_id), INDEX IDX_3FFAFD4AC4598A51 (emplacement_id), INDEX IDX_3FFAFD4AD5E86FF (etat_id), INDEX IDX_3FFAFD4A670C757F (fournisseur_id), INDEX IDX_3FFAFD4AA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imprimante (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, contrat TINYINT(1) NOT NULL, date_installation VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_4DF2C3AAC4598A51 (emplacement_id), INDEX IDX_4DF2C3AAD5E86FF (etat_id), INDEX IDX_4DF2C3AA670C757F (fournisseur_id), INDEX IDX_4DF2C3AAA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE onduleur (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, capacite INT DEFAULT NULL, type_prise VARCHAR(255) DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_C698A4E6C4598A51 (emplacement_id), INDEX IDX_C698A4E6D5E86FF (etat_id), INDEX IDX_C698A4E6670C757F (fournisseur_id), INDEX IDX_C698A4E6A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pc_fixe (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, stockage_id INT DEFAULT NULL, fournisseur_id INT DEFAULT NULL, systeme_exploitation_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, stockage_type VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, INDEX IDX_C65214E2FB88E14F (utilisateur_id), INDEX IDX_C65214E2C4598A51 (emplacement_id), INDEX IDX_C65214E2D5E86FF (etat_id), INDEX IDX_C65214E2DAA83D7F (stockage_id), INDEX IDX_C65214E2670C757F (fournisseur_id), INDEX IDX_C65214E21B83D934 (systeme_exploitation_id), INDEX IDX_C65214E2A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pc_portable (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, stockage_id INT DEFAULT NULL, fournisseur_id INT DEFAULT NULL, systeme_exploitation_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, stockage_type VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, INDEX IDX_A5D1ABC6FB88E14F (utilisateur_id), INDEX IDX_A5D1ABC6C4598A51 (emplacement_id), INDEX IDX_A5D1ABC6D5E86FF (etat_id), INDEX IDX_A5D1ABC6DAA83D7F (stockage_id), INDEX IDX_A5D1ABC6670C757F (fournisseur_id), INDEX IDX_A5D1ABC61B83D934 (systeme_exploitation_id), INDEX IDX_A5D1ABC6A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serveur (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, stockage_id INT DEFAULT NULL, fournisseur_id INT DEFAULT NULL, systeme_exploitation_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, processeur VARCHAR(255) DEFAULT NULL, memoire INT DEFAULT NULL, stockage_nombre INT DEFAULT NULL, physique TINYINT(1) NOT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_contrat VARCHAR(255) DEFAULT NULL, INDEX IDX_77CC53A6C4598A51 (emplacement_id), INDEX IDX_77CC53A6D5E86FF (etat_id), INDEX IDX_77CC53A6DAA83D7F (stockage_id), INDEX IDX_77CC53A6670C757F (fournisseur_id), INDEX IDX_77CC53A61B83D934 (systeme_exploitation_id), INDEX IDX_77CC53A6A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stockage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE systeme_exploitation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tablette (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, emplacement_id INT NOT NULL, etat_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, INDEX IDX_508CDDD7FB88E14F (utilisateur_id), INDEX IDX_508CDDD7C4598A51 (emplacement_id), INDEX IDX_508CDDD7D5E86FF (etat_id), INDEX IDX_508CDDD7670C757F (fournisseur_id), INDEX IDX_508CDDD7A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telephone_fixe (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, fournisseur_id INT DEFAULT NULL, emplacement_id INT DEFAULT NULL, etat_id INT NOT NULL, entreprise_id INT DEFAULT NULL, ligne VARCHAR(255) DEFAULT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, numero_serie VARCHAR(255) DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_9FC0EC06FB88E14F (utilisateur_id), INDEX IDX_9FC0EC06670C757F (fournisseur_id), INDEX IDX_9FC0EC06C4598A51 (emplacement_id), INDEX IDX_9FC0EC06D5E86FF (etat_id), INDEX IDX_9FC0EC06A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telephone_portable (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, etat_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, ligne VARCHAR(255) DEFAULT NULL, marque VARCHAR(255) DEFAULT NULL, modele VARCHAR(255) DEFAULT NULL, numero_serie VARCHAR(255) DEFAULT NULL, imei1 VARCHAR(255) DEFAULT NULL, imei2 VARCHAR(255) DEFAULT NULL, date_achat VARCHAR(255) DEFAULT NULL, date_garantie VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, date_installation VARCHAR(255) DEFAULT NULL, INDEX IDX_16DB5E09FB88E14F (utilisateur_id), INDEX IDX_16DB5E09D5E86FF (etat_id), INDEX IDX_16DB5E09670C757F (fournisseur_id), INDEX IDX_16DB5E09A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_1D1C63B3A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4A670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE imprimante ADD CONSTRAINT FK_4DF2C3AAC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE imprimante ADD CONSTRAINT FK_4DF2C3AAD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE imprimante ADD CONSTRAINT FK_4DF2C3AA670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE imprimante ADD CONSTRAINT FK_4DF2C3AAA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE onduleur ADD CONSTRAINT FK_C698A4E6C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE onduleur ADD CONSTRAINT FK_C698A4E6D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE onduleur ADD CONSTRAINT FK_C698A4E6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE onduleur ADD CONSTRAINT FK_C698A4E6A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E21B83D934 FOREIGN KEY (systeme_exploitation_id) REFERENCES systeme_exploitation (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD CONSTRAINT FK_C65214E2A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC61B83D934 FOREIGN KEY (systeme_exploitation_id) REFERENCES systeme_exploitation (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pc_portable ADD CONSTRAINT FK_A5D1ABC6A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6DAA83D7F FOREIGN KEY (stockage_id) REFERENCES stockage (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A61B83D934 FOREIGN KEY (systeme_exploitation_id) REFERENCES systeme_exploitation (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id)');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tablette ADD CONSTRAINT FK_508CDDD7A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_fixe ADD CONSTRAINT FK_9FC0EC06FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_fixe ADD CONSTRAINT FK_9FC0EC06670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_fixe ADD CONSTRAINT FK_9FC0EC06C4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_fixe ADD CONSTRAINT FK_9FC0EC06D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE telephone_fixe ADD CONSTRAINT FK_9FC0EC06A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_portable ADD CONSTRAINT FK_16DB5E09FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_portable ADD CONSTRAINT FK_16DB5E09D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE telephone_portable ADD CONSTRAINT FK_16DB5E09670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE telephone_portable ADD CONSTRAINT FK_16DB5E09A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AFB88E14F');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AC4598A51');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AD5E86FF');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4A670C757F');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AA4AEAFEA');
        $this->addSql('ALTER TABLE imprimante DROP FOREIGN KEY FK_4DF2C3AAC4598A51');
        $this->addSql('ALTER TABLE imprimante DROP FOREIGN KEY FK_4DF2C3AAD5E86FF');
        $this->addSql('ALTER TABLE imprimante DROP FOREIGN KEY FK_4DF2C3AA670C757F');
        $this->addSql('ALTER TABLE imprimante DROP FOREIGN KEY FK_4DF2C3AAA4AEAFEA');
        $this->addSql('ALTER TABLE onduleur DROP FOREIGN KEY FK_C698A4E6C4598A51');
        $this->addSql('ALTER TABLE onduleur DROP FOREIGN KEY FK_C698A4E6D5E86FF');
        $this->addSql('ALTER TABLE onduleur DROP FOREIGN KEY FK_C698A4E6670C757F');
        $this->addSql('ALTER TABLE onduleur DROP FOREIGN KEY FK_C698A4E6A4AEAFEA');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2FB88E14F');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2C4598A51');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2D5E86FF');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2DAA83D7F');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2670C757F');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E21B83D934');
        $this->addSql('ALTER TABLE pc_fixe DROP FOREIGN KEY FK_C65214E2A4AEAFEA');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6FB88E14F');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6C4598A51');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6D5E86FF');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6DAA83D7F');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6670C757F');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC61B83D934');
        $this->addSql('ALTER TABLE pc_portable DROP FOREIGN KEY FK_A5D1ABC6A4AEAFEA');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6C4598A51');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6D5E86FF');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6DAA83D7F');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6670C757F');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A61B83D934');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6A4AEAFEA');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7FB88E14F');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7C4598A51');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7D5E86FF');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7670C757F');
        $this->addSql('ALTER TABLE tablette DROP FOREIGN KEY FK_508CDDD7A4AEAFEA');
        $this->addSql('ALTER TABLE telephone_fixe DROP FOREIGN KEY FK_9FC0EC06FB88E14F');
        $this->addSql('ALTER TABLE telephone_fixe DROP FOREIGN KEY FK_9FC0EC06670C757F');
        $this->addSql('ALTER TABLE telephone_fixe DROP FOREIGN KEY FK_9FC0EC06C4598A51');
        $this->addSql('ALTER TABLE telephone_fixe DROP FOREIGN KEY FK_9FC0EC06D5E86FF');
        $this->addSql('ALTER TABLE telephone_fixe DROP FOREIGN KEY FK_9FC0EC06A4AEAFEA');
        $this->addSql('ALTER TABLE telephone_portable DROP FOREIGN KEY FK_16DB5E09FB88E14F');
        $this->addSql('ALTER TABLE telephone_portable DROP FOREIGN KEY FK_16DB5E09D5E86FF');
        $this->addSql('ALTER TABLE telephone_portable DROP FOREIGN KEY FK_16DB5E09670C757F');
        $this->addSql('ALTER TABLE telephone_portable DROP FOREIGN KEY FK_16DB5E09A4AEAFEA');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3A4AEAFEA');
        $this->addSql('DROP TABLE ecran');
        $this->addSql('DROP TABLE emplacement');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE imprimante');
        $this->addSql('DROP TABLE onduleur');
        $this->addSql('DROP TABLE pc_fixe');
        $this->addSql('DROP TABLE pc_portable');
        $this->addSql('DROP TABLE serveur');
        $this->addSql('DROP TABLE stockage');
        $this->addSql('DROP TABLE systeme_exploitation');
        $this->addSql('DROP TABLE tablette');
        $this->addSql('DROP TABLE telephone_fixe');
        $this->addSql('DROP TABLE telephone_portable');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE utilisateur');
    }
}
