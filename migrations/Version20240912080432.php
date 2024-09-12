<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912080432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, note_id INT NOT NULL, creator_id INT NOT NULL, UNIQUE INDEX UNIQ_AC6340B326ED0855 (note_id), INDEX IDX_AC6340B361220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE network (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_608487BC61220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B326ED0855 FOREIGN KEY (note_id) REFERENCES note (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B361220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE network ADD CONSTRAINT FK_608487BC61220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B326ED0855');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B361220EA6');
        $this->addSql('ALTER TABLE network DROP FOREIGN KEY FK_608487BC61220EA6');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE network');
        $this->addSql('ALTER TABLE `user` DROP image');
    }
}
