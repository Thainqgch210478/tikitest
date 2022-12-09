<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209045424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, productid_id INT NOT NULL, cusid_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_BA388B7AF89CCED (productid_id), INDEX IDX_BA388B7B1C69198 (cusid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, cusid_id INT NOT NULL, address VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, paymentmethod VARCHAR(255) NOT NULL, date DATE NOT NULL, transportationmethod VARCHAR(255) NOT NULL, INDEX IDX_F5299398B1C69198 (cusid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, orderid_id INT NOT NULL, productid_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_845CA2C16F90D45B (orderid_id), INDEX IDX_845CA2C1AF89CCED (productid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, categoryid_id INT NOT NULL, brandid_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, image1 VARCHAR(255) NOT NULL, image2 VARCHAR(255) NOT NULL, image3 VARCHAR(255) NOT NULL, INDEX IDX_D34A04ADA9FA940B (categoryid_id), INDEX IDX_D34A04AD506E4F3 (brandid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_detail (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, dob DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4B5464AE58E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7AF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7B1C69198 FOREIGN KEY (cusid_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B1C69198 FOREIGN KEY (cusid_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C16F90D45B FOREIGN KEY (orderid_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1AF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA9FA940B FOREIGN KEY (categoryid_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD506E4F3 FOREIGN KEY (brandid_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE user_detail ADD CONSTRAINT FK_4B5464AE58E0A285 FOREIGN KEY (userid_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7AF89CCED');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7B1C69198');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B1C69198');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C16F90D45B');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1AF89CCED');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA9FA940B');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD506E4F3');
        $this->addSql('ALTER TABLE user_detail DROP FOREIGN KEY FK_4B5464AE58E0A285');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_detail');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
