<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918192347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_in_cart (id INT AUTO_INCREMENT NOT NULL, fk_product_id INT NOT NULL, quantity INT NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE252723653981');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527879D5689');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD0877');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25279D106E90');
        $this->addSql('ALTER TABLE checkout_form_data DROP FOREIGN KEY FK_6048EFC1879D5689');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE checkout_form_data');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B71AD0877');
        $this->addSql('DROP INDEX IDX_BA388B71AD0877 ON cart');
        $this->addSql('ALTER TABLE cart ADD fk_product_id INT NOT NULL, ADD fk_user_id INT NOT NULL, ADD fk_shipping_id INT NOT NULL, ADD fk_cart_item INT NOT NULL, DROP fk_user, CHANGE fk_checkout promocode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491AD0877');
        $this->addSql('DROP INDEX IDX_8D93D6491AD0877 ON user');
        $this->addSql('ALTER TABLE user DROP fk_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, fk_cart INT DEFAULT NULL, fk_user INT DEFAULT NULL, fk_checkout INT DEFAULT NULL, fk_product INT DEFAULT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, image_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image_size INT NOT NULL, image_file VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_F0FE252723653981 (fk_product), INDEX IDX_F0FE2527879D5689 (fk_cart), INDEX IDX_F0FE25271AD0877 (fk_user), INDEX IDX_F0FE25279D106E90 (fk_checkout), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE checkout_form_data (id INT AUTO_INCREMENT NOT NULL, fk_cart INT DEFAULT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, country VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, state VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, zip INT NOT NULL, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, shipping_address_same TINYINT(1) DEFAULT NULL, save_info TINYINT(1) DEFAULT NULL, payment_method INT DEFAULT NULL, promo_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_6048EFC1879D5689 (fk_cart), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE252723653981 FOREIGN KEY (fk_product) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527879D5689 FOREIGN KEY (fk_cart) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD0877 FOREIGN KEY (fk_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25279D106E90 FOREIGN KEY (fk_checkout) REFERENCES checkout_form_data (id)');
        $this->addSql('ALTER TABLE checkout_form_data ADD CONSTRAINT FK_6048EFC1879D5689 FOREIGN KEY (fk_cart) REFERENCES cart (id)');
        $this->addSql('DROP TABLE product_in_cart');
        $this->addSql('ALTER TABLE cart ADD fk_user INT DEFAULT NULL, DROP fk_product_id, DROP fk_user_id, DROP fk_shipping_id, DROP fk_cart_item, CHANGE promocode fk_checkout VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B71AD0877 FOREIGN KEY (fk_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA388B71AD0877 ON cart (fk_user)');
        $this->addSql('ALTER TABLE user ADD fk_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491AD0877 FOREIGN KEY (fk_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491AD0877 ON user (fk_user)');
    }
}
