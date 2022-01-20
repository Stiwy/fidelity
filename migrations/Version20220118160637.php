<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118160637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(10) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, updated_date DATETIME DEFAULT NULL, insert_date DATETIME NOT NULL, last_store_visit DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_offer (customer_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_E7E3F2059395C3F3 (customer_id), INDEX IDX_E7E3F20553C674EE (offer_id), PRIMARY KEY(customer_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, updated_date DATETIME DEFAULT NULL, insert_date DATETIME NOT NULL, notified_on DATETIME DEFAULT NULL, new_customer TINYINT(1) NOT NULL, all_customer TINYINT(1) NOT NULL, start_offer_on DATETIME DEFAULT NULL, end_offer_on DATETIME DEFAULT NULL, notified_after_store INT DEFAULT NULL, after_visit_store TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_offer ADD CONSTRAINT FK_E7E3F2059395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_offer ADD CONSTRAINT FK_E7E3F20553C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_offer DROP FOREIGN KEY FK_E7E3F2059395C3F3');
        $this->addSql('ALTER TABLE customer_offer DROP FOREIGN KEY FK_E7E3F20553C674EE');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE user');
    }
}
