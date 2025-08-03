<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803231940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO product (name, price, created_at) VALUES
            ('Product 1', 19.99, '2025-08-04'),
            ('Product 2', 14.50, '2025-08-04'),
            ('Product 3', 39.90, '2025-08-04'),
            ('Product 4', 29.00, '2025-08-04'),
            ('Product 5', 50.00, '2025-08-04')
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
