<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118140842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD last_store_visit DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD notified_on DATETIME DEFAULT NULL, ADD notified_after_store DATETIME DEFAULT NULL, ADD new_customer TINYINT(1) NOT NULL, ADD all_customer TINYINT(1) NOT NULL, ADD start_offer_on DATETIME DEFAULT NULL, ADD end_offer_on DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP last_store_visit');
        $this->addSql('ALTER TABLE offer DROP notified_on, DROP notified_after_store, DROP new_customer, DROP all_customer, DROP start_offer_on, DROP end_offer_on');
    }
}
