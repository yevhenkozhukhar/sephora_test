<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129205706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sales_order (id INT AUTO_INCREMENT NOT NULL, currency VARCHAR(3) NOT NULL, date DATE NOT NULL, total INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_5DD6A8658D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sales_order_item ADD CONSTRAINT FK_5DD6A8658D9F6D38 FOREIGN KEY (order_id) REFERENCES sales_order (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sales_order_item DROP FOREIGN KEY FK_5DD6A8658D9F6D38');
        $this->addSql('DROP TABLE sales_order');
        $this->addSql('DROP TABLE sales_order_item');
    }
}
