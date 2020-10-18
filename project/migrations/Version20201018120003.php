<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018120003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_product (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnalisation (id INT AUTO_INCREMENT NOT NULL, personalisation_id INT DEFAULT NULL, INDEX IDX_C9B3EAE63760A92A (personalisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personnalisation ADD CONSTRAINT FK_C9B3EAE63760A92A FOREIGN KEY (personalisation_id) REFERENCES panier_product (id)');
        $this->addSql('ALTER TABLE product ADD product_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDE18E50B FOREIGN KEY (product_id_id) REFERENCES panier_product (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADDE18E50B ON product (product_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnalisation DROP FOREIGN KEY FK_C9B3EAE63760A92A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDE18E50B');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_product');
        $this->addSql('DROP TABLE personnalisation');
        $this->addSql('DROP INDEX IDX_D34A04ADDE18E50B ON product');
        $this->addSql('ALTER TABLE product DROP product_id_id');
    }
}
