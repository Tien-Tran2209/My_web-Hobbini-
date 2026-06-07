<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260606215632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item CHANGE cart_id cart_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD cancelled_by VARCHAR(20) DEFAULT NULL, ADD user_order_number INT NOT NULL, ADD payment_method VARCHAR(30) DEFAULT NULL, ADD payment_status VARCHAR(30) NOT NULL, CHANGE status status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE product ADD stock INT NOT NULL, ADD sold INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item CHANGE cart_id cart_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` DROP cancelled_by, DROP user_order_number, DROP payment_method, DROP payment_status, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product DROP stock, DROP sold');
    }
}
