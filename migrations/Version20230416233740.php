<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416233740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE photo photo LONGBLOB NOT NULL');
        $this->addSql('DROP INDEX id_evenement ON ticket');
        $this->addSql('ALTER TABLE ticket ADD idEvenement INT DEFAULT NULL, DROP id_evenement');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F7CC4348 FOREIGN KEY (idEvenement) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX idEvenement ON ticket (idEvenement)');
        $this->addSql('ALTER TABLE topic CHANGE titretopic titretopic VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE photo photo LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F7CC4348');
        $this->addSql('DROP INDEX idEvenement ON ticket');
        $this->addSql('ALTER TABLE ticket ADD id_evenement INT NOT NULL, DROP idEvenement');
        $this->addSql('CREATE INDEX id_evenement ON ticket (id_evenement)');
        $this->addSql('ALTER TABLE topic CHANGE titretopic titretopic VARCHAR(255) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\'');
    }
}
