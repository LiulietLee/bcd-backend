<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180509043823 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE search_record (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, nid INT NOT NULL, time DATETIME NOT NULL, cover_url VARCHAR(255) DEFAULT NULL, download_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cover_record (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, nid INT NOT NULL, time DATETIME NOT NULL, url VARCHAR(255) NOT NULL, dlcount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE searchRecord');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE searchRecord (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, nid INT NOT NULL, time DATETIME NOT NULL, coverURL VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, downloadCount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE search_record');
        $this->addSql('DROP TABLE cover_record');
    }
}
