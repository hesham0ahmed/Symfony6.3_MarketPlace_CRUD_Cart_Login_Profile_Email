<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922083304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_in_cart DROP FOREIGN KEY FK_F0C1AC634584665A');
        $this->addSql('DROP INDEX IDX_F0C1AC634584665A ON product_in_cart');
        $this->addSql('ALTER TABLE product_in_cart DROP product_id, CHANGE fk_user_id fk_user_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_in_cart ADD product_id INT DEFAULT NULL, CHANGE fk_user_id fk_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_in_cart ADD CONSTRAINT FK_F0C1AC634584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_F0C1AC634584665A ON product_in_cart (product_id)');
    }
}
