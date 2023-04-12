<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402051041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cloture_achat (id_cloture INT AUTO_INCREMENT NOT NULL, id_details_livraison INT DEFAULT NULL, pointAchatFinale INT DEFAULT NULL, etatCloture VARCHAR(45) DEFAULT \'NULL\', etatLivre VARCHAR(45) DEFAULT \'NULL\', INDEX foreign_idx (id_details_livraison), PRIMARY KEY(id_cloture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (idCommande INT AUTO_INCREMENT NOT NULL, idClient INT NOT NULL, adresselivraison VARCHAR(255) NOT NULL, etatcommande VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX idc (idClient), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandelignelivre (idLigne INT AUTO_INCREMENT NOT NULL, idLivre INT NOT NULL, idCommande INT NOT NULL, INDEX fk_cmdd (idCommande), INDEX fk_cmd (idLivre), PRIMARY KEY(idLigne)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (idcom INT AUTO_INCREMENT NOT NULL, idsujet INT DEFAULT NULL, iduser INT DEFAULT NULL, contenu TEXT NOT NULL, date DATE NOT NULL, nblike INT NOT NULL, nbdislike INT NOT NULL, INDEX idsujcom (idsujet), INDEX idccom (iduser), PRIMARY KEY(idcom)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detailslivraison (idDetailsLivraison INT AUTO_INCREMENT NOT NULL, etatLivrasion VARCHAR(45) DEFAULT \'NULL\', AdresseLivraison VARCHAR(45) DEFAULT \'NULL\', idEstimationOffreLivre INT DEFAULT NULL, INDEX fore_idx (idEstimationOffreLivre), PRIMARY KEY(idDetailsLivraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dislikee (id_dislike INT AUTO_INCREMENT NOT NULL, id_commentaire INT DEFAULT NULL, id_user INT DEFAULT NULL, INDEX idcdis (id_user), INDEX idcomm (id_commentaire), PRIMARY KEY(id_dislike)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimationoffrelivre (idEstimationOffreLivre INT AUTO_INCREMENT NOT NULL, pointEstime INT DEFAULT NULL, etat VARCHAR(45) DEFAULT \'NULL\', idProposition INT DEFAULT NULL, INDEX for_idx (idProposition), PRIMARY KEY(idEstimationOffreLivre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, emplacement VARCHAR(255) NOT NULL, nb_place INT NOT NULL, duree INT NOT NULL, INDEX idcevent (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likee (id_like INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_commentaire INT DEFAULT NULL, INDEX idclike (id_user), INDEX idcomlike (id_commentaire), PRIMARY KEY(id_like)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (idLivre INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT \'NULL\', categorie VARCHAR(255) DEFAULT \'NULL\', datePublication DATETIME DEFAULT NULL, image BLOB DEFAULT \'NULL\', description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, descriptionEtatLivre VARCHAR(255) NOT NULL, note INT NOT NULL, PRIMARY KEY(idLivre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propositionlivre (idclient INT DEFAULT NULL, idPropositionLivre INT AUTO_INCREMENT NOT NULL, titreLivre VARCHAR(45) DEFAULT \'NULL\', editon VARCHAR(45) NOT NULL, dateProposition DATE DEFAULT NULL, descriptionEtat VARCHAR(45) DEFAULT \'NULL\', INDEX idcproplivre (idclient), PRIMARY KEY(idPropositionLivre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_reponse INT DEFAULT NULL, type VARCHAR(30) NOT NULL, sujet VARCHAR(100) NOT NULL, description VARCHAR(8000) NOT NULL, date DATE DEFAULT NULL, photo LONGBLOB NOT NULL, status VARCHAR(20) NOT NULL, INDEX idcrec (id_user), INDEX idreprec (id_reponse), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, contenu TEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (idsujet INT AUTO_INCREMENT NOT NULL, idtopic INT DEFAULT NULL, iduser INT DEFAULT NULL, titresujet VARCHAR(255) NOT NULL, contenu TEXT NOT NULL, date DATE NOT NULL, accepter INT NOT NULL, nbcom INT NOT NULL, INDEX idsujtopic (idtopic), INDEX idcsujet (iduser), PRIMARY KEY(idsujet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, prix INT NOT NULL, type VARCHAR(255) NOT NULL, id_evenement INT NOT NULL, INDEX id_evenement (id_evenement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (idtopic INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, titretopic VARCHAR(255) DEFAULT \'NULL\', description VARCHAR(255) DEFAULT \'NULL\', date DATE DEFAULT NULL, accepter TINYINT(1) NOT NULL, nbsujet INT NOT NULL, hide INT NOT NULL, INDEX idctopic (iduser), PRIMARY KEY(idtopic)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(25) NOT NULL, prenom VARCHAR(25) NOT NULL, adrmail VARCHAR(25) NOT NULL, mdp VARCHAR(25) NOT NULL, adresse VARCHAR(100) NOT NULL, tel VARCHAR(25) NOT NULL, type VARCHAR(6) NOT NULL, cin INT NOT NULL, soldepoint INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloture_achat ADD CONSTRAINT FK_3D072CDB2D3BFBD3 FOREIGN KEY (id_details_livraison) REFERENCES detailslivraison (idDetailsLivraison)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF8163584 FOREIGN KEY (idsujet) REFERENCES sujet (idsujet)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detailslivraison ADD CONSTRAINT FK_E29AA9F41737DF93 FOREIGN KEY (idEstimationOffreLivre) REFERENCES estimationoffrelivre (idEstimationOffreLivre)');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT FK_D9270B407FE2A54B FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT FK_D9270B406B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE estimationoffrelivre ADD CONSTRAINT FK_B9C1167787903C2B FOREIGN KEY (idProposition) REFERENCES propositionlivre (idPropositionLivre)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT FK_BD1EFB2C6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT FK_BD1EFB2C7FE2A54B FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE propositionlivre ADD CONSTRAINT FK_AAE4FFF0A3F9A9F9 FOREIGN KEY (idclient) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404812B77B7 FOREIGN KEY (id_reponse) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D4B45B202 FOREIGN KEY (idtopic) REFERENCES topic (idtopic)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cloture_achat DROP FOREIGN KEY FK_3D072CDB2D3BFBD3');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF8163584');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5E5C27E9');
        $this->addSql('ALTER TABLE detailslivraison DROP FOREIGN KEY FK_E29AA9F41737DF93');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY FK_D9270B407FE2A54B');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY FK_D9270B406B3CA4B');
        $this->addSql('ALTER TABLE estimationoffrelivre DROP FOREIGN KEY FK_B9C1167787903C2B');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6B3CA4B');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY FK_BD1EFB2C6B3CA4B');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY FK_BD1EFB2C7FE2A54B');
        $this->addSql('ALTER TABLE propositionlivre DROP FOREIGN KEY FK_AAE4FFF0A3F9A9F9');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404812B77B7');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D4B45B202');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D5E5C27E9');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B5E5C27E9');
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
        $this->addSql('DROP TABLE messenger_messages');
    }
}
