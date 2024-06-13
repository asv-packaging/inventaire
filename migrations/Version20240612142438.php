<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612142438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecran ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE emplacement ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE entreprise ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE etat ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE imprimante ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE onduleur ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pc_fixe ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pc_portable ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE serveur ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE stockage ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE systeme_exploitation ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tablette ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE telephone_fixe ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE telephone_portable ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecran DROP slug');
        $this->addSql('ALTER TABLE emplacement DROP slug');
        $this->addSql('ALTER TABLE entreprise DROP slug');
        $this->addSql('ALTER TABLE etat DROP slug');
        $this->addSql('ALTER TABLE fournisseur DROP slug');
        $this->addSql('ALTER TABLE imprimante DROP slug');
        $this->addSql('ALTER TABLE onduleur DROP slug');
        $this->addSql('ALTER TABLE pc_fixe DROP slug');
        $this->addSql('ALTER TABLE pc_portable DROP slug');
        $this->addSql('ALTER TABLE serveur DROP slug');
        $this->addSql('ALTER TABLE stockage DROP slug');
        $this->addSql('ALTER TABLE systeme_exploitation DROP slug');
        $this->addSql('ALTER TABLE tablette DROP slug');
        $this->addSql('ALTER TABLE telephone_fixe DROP slug');
        $this->addSql('ALTER TABLE telephone_portable DROP slug');
        $this->addSql('ALTER TABLE utilisateur DROP slug');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
