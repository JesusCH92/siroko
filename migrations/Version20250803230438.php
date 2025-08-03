<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803230438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "INSERT INTO user (email, password, roles, created_at) VALUES (
        'admin',
        '\$2y\$13\$1EDntJO3ubwjv9Ute.eR9u6h9E3yFIR68k51krhsjOGCWnHL/yv4W',
        '[\"ROLE_USER\"]',
        '2025-08-04'
    )"
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
