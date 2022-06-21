<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621074655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles MODIFY id_article INT NOT NULL');
        $this->addSql('ALTER TABLE articles DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE articles CHANGE date_achat date_achat DATETIME NOT NULL, CHANGE zone_saisie zone_saisie LONGTEXT DEFAULT NULL, CHANGE notice notice LONGTEXT DEFAULT NULL, CHANGE id_article id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE categories MODIFY id_categorie INT NOT NULL');
        $this->addSql('ALTER TABLE categories DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE categories CHANGE id_categorie id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE categories ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE lieux_achats MODIFY id_lieu INT NOT NULL');
        $this->addSql('ALTER TABLE lieux_achats DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lieux_achats CHANGE id_lieu id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE lieux_achats ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE users MODIFY id_user INT NOT NULL');
        $this->addSql('DROP INDEX pseudo ON users');
        $this->addSql('DROP INDEX mail ON users');
        $this->addSql('ALTER TABLE users DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE users CHANGE id_user id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE users ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE articles DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE articles CHANGE date_achat date_achat DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE zone_saisie zone_saisie TEXT DEFAULT NULL, CHANGE notice notice TEXT DEFAULT NULL, CHANGE id id_article INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD PRIMARY KEY (id_article)');
        $this->addSql('ALTER TABLE categories MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE categories DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE categories CHANGE id id_categorie INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE categories ADD PRIMARY KEY (id_categorie)');
        $this->addSql('ALTER TABLE lieux_achats MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE lieux_achats DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE lieux_achats CHANGE id id_lieu INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE lieux_achats ADD PRIMARY KEY (id_lieu)');
        $this->addSql('ALTER TABLE users MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE users DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE users CHANGE id id_user INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX pseudo ON users (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX mail ON users (mail)');
        $this->addSql('ALTER TABLE users ADD PRIMARY KEY (id_user)');
    }
}
