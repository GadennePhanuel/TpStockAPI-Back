<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918111219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, price INT NOT NULL, ref VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE belong (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, stock_id INT NOT NULL, qty INT NOT NULL, INDEX IDX_BFFF81BB7294869C (article_id), INDEX IDX_BFFF81BBDCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE belong ADD CONSTRAINT FK_BFFF81BB7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE belong ADD CONSTRAINT FK_BFFF81BBDCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE belong DROP FOREIGN KEY FK_BFFF81BB7294869C');
        $this->addSql('ALTER TABLE belong DROP FOREIGN KEY FK_BFFF81BBDCD6110');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE belong');
        $this->addSql('DROP TABLE stock');
    }
}
