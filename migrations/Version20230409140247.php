<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409140247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propositionlivre CHANGE idclient idclient INT DEFAULT NULL, CHANGE titreLivre titreLivre VARCHAR(45) DEFAULT \'NULL\', CHANGE descriptionEtat descriptionEtat VARCHAR(300) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY ticket_ibfk_1');
        $this->addSql('ALTER TABLE topic CHANGE titretopic titretopic VARCHAR(255) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\', CHANGE accepter accepter TINYINT(1) NOT NULL, CHANGE nbsujet nbsujet INT NOT NULL, CHANGE hide hide INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propositionlivre CHANGE idclient idclient INT NOT NULL, CHANGE titreLivre titreLivre VARCHAR(45) DEFAULT NULL, CHANGE descriptionEtat descriptionEtat VARCHAR(45) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT ticket_ibfk_1 FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic CHANGE titretopic titretopic VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE accepter accepter TINYINT(1) DEFAULT 0 NOT NULL, CHANGE nbsujet nbsujet INT DEFAULT 0 NOT NULL, CHANGE hide hide INT DEFAULT 0 NOT NULL');
    }
}
