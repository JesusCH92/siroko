<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250802201528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD deleted_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE product ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD deleted_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE user ADD created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD deleted_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP created_at, DROP deleted_at');
        $this->addSql('ALTER TABLE cart_item DROP created_at, DROP deleted_at');
        $this->addSql('ALTER TABLE product DROP created_at, DROP deleted_at');
    }
}
