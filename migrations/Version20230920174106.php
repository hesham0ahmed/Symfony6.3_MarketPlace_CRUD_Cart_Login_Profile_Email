<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230920174106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add the fk_user_id column to the product_in_cart table
        $this->addSql('ALTER TABLE product_in_cart ADD fk_user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove the fk_user_id column if needed in the down migration
        $this->addSql('ALTER TABLE product_in_cart DROP fk_user_id');
    }
}
