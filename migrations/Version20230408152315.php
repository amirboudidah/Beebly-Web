<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408152315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloture_achat DROP FOREIGN KEY `foreign`');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY idsujcom');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY idccom');
        $this->addSql('ALTER TABLE detailslivraison DROP FOREIGN KEY fore');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY idcomm');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY idcdis');
        $this->addSql('ALTER TABLE estimationoffrelivre DROP FOREIGN KEY `for`');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY idcevent');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY idclike');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY idcomlike');
        $this->addSql('ALTER TABLE propositionlivre DROP FOREIGN KEY idcproplivre');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY idcrec');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY idreprec');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY idsujtopic');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY idcsujet');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY ticket_ibfk_1');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY idctopic');
        $this->addSql('DROP TABLE cloture_achat');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commandelignelivre');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE detailslivraison');
        $this->addSql('DROP TABLE dislikee');
        $this->addSql('DROP TABLE estimationoffrelivre');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE likee');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE propositionlivre');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cloture_achat (id_cloture INT AUTO_INCREMENT NOT NULL, id_details_livraison INT DEFAULT NULL, pointAchatFinale INT DEFAULT NULL, etatCloture VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, etatLivre VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX foreign_idx (id_details_livraison), PRIMARY KEY(id_cloture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (idCommande INT AUTO_INCREMENT NOT NULL, idClient INT NOT NULL, adresselivraison VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, etatcommande VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, prix DOUBLE PRECISION NOT NULL, INDEX idc (idClient), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandelignelivre (idLigne INT AUTO_INCREMENT NOT NULL, idLivre INT NOT NULL, idCommande INT NOT NULL, INDEX fk_cmdd (idCommande), INDEX fk_cmd (idLivre), PRIMARY KEY(idLigne)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commentaire (idcom INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idsujet INT DEFAULT NULL, contenu TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, nblike INT DEFAULT 0 NOT NULL, nbdislike INT DEFAULT 0 NOT NULL, INDEX idsujcom (idsujet), INDEX idccom (iduser), PRIMARY KEY(idcom)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE detailslivraison (idDetailsLivraison INT AUTO_INCREMENT NOT NULL, etatLivrasion VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, idEstimationOffreLivre INT DEFAULT NULL, AdresseLivraison VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX fore_idx (idEstimationOffreLivre), PRIMARY KEY(idDetailsLivraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dislikee (id_dislike INT AUTO_INCREMENT NOT NULL, id_commentaire INT NOT NULL, id_user INT NOT NULL, INDEX idcdis (id_user), INDEX idcomm (id_commentaire), PRIMARY KEY(id_dislike)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE estimationoffrelivre (idEstimationOffreLivre INT AUTO_INCREMENT NOT NULL, idProposition INT DEFAULT NULL, pointEstime INT DEFAULT NULL, etat VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX for_idx (idProposition), PRIMARY KEY(idEstimationOffreLivre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, emplacement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nb_place INT NOT NULL, duree INT NOT NULL, INDEX idcevent (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE likee (id_like INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_commentaire INT NOT NULL, INDEX idclike (id_user), INDEX idcomlike (id_commentaire), PRIMARY KEY(id_like)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livre (idLivre INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, categorie VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, datePublication DATETIME DEFAULT NULL, image BLOB DEFAULT NULL, description VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, prix DOUBLE PRECISION NOT NULL, descriptionEtatLivre VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, note INT NOT NULL, PRIMARY KEY(idLivre)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE propositionlivre (idclient INT NOT NULL, idPropositionLivre INT AUTO_INCREMENT NOT NULL, titreLivre VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, editon VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateProposition DATE DEFAULT NULL, descriptionEtat VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX idcproplivre (idclient), PRIMARY KEY(idPropositionLivre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_reponse INT DEFAULT NULL, type VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, sujet VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(8000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE DEFAULT NULL, photo LONGBLOB NOT NULL, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX idcrec (id_user), INDEX idreprec (id_reponse), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, contenu TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sujet (idsujet INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idtopic INT DEFAULT NULL, titresujet VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, contenu TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, accepter INT NOT NULL, nbcom INT NOT NULL, INDEX idsujtopic (idtopic), INDEX idcsujet (iduser), PRIMARY KEY(idsujet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, id_evenement INT NOT NULL, prix INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_evenement (id_evenement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE topic (idtopic INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, titretopic VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date DATE DEFAULT NULL, accepter TINYINT(1) DEFAULT 0 NOT NULL, nbsujet INT DEFAULT 0 NOT NULL, hide INT DEFAULT 0 NOT NULL, INDEX idctopic (iduser), PRIMARY KEY(idtopic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adrmail VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresse VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, tel VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(6) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, cin INT NOT NULL, soldepoint INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cloture_achat ADD CONSTRAINT `foreign` FOREIGN KEY (id_details_livraison) REFERENCES detailslivraison (idDetailsLivraison)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT idsujcom FOREIGN KEY (idsujet) REFERENCES sujet (idsujet)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT idccom FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detailslivraison ADD CONSTRAINT fore FOREIGN KEY (idEstimationOffreLivre) REFERENCES estimationoffrelivre (idEstimationOffreLivre)');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT idcomm FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT idcdis FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE estimationoffrelivre ADD CONSTRAINT `for` FOREIGN KEY (idProposition) REFERENCES propositionlivre (idPropositionLivre)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT idcevent FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT idclike FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT idcomlike FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE propositionlivre ADD CONSTRAINT idcproplivre FOREIGN KEY (idclient) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT idcrec FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT idreprec FOREIGN KEY (id_reponse) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT idsujtopic FOREIGN KEY (idtopic) REFERENCES topic (idtopic)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT idcsujet FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT ticket_ibfk_1 FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT idctopic FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
