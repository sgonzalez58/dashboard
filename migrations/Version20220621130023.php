<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621130023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF346686C6E55B5 ON categories (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E986CC499D ON users (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E95126AC48 ON users (mail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3AF346686C6E55B5 ON categories');
        $this->addSql('DROP INDEX UNIQ_1483A5E986CC499D ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E95126AC48 ON users');
    }
}
