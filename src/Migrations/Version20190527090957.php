<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190527090957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE p5_users ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE p5_users ADD CONSTRAINT FK_DD9103DE3DA5256D FOREIGN KEY (image_id) REFERENCES image_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD9103DE3DA5256D ON p5_users (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p5_Users DROP FOREIGN KEY FK_DD9103DE3DA5256D');
        $this->addSql('DROP TABLE image_user');
        $this->addSql('DROP INDEX UNIQ_DD9103DE3DA5256D ON p5_Users');
        $this->addSql('ALTER TABLE p5_Users DROP image_id');
    }
}
