<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531095627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE topic_like (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_948821061F55203D (topic_id), INDEX IDX_94882106A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE topic_like ADD CONSTRAINT FK_948821061F55203D FOREIGN KEY (topic_id) REFERENCES p5_Topics (id)');
        $this->addSql('ALTER TABLE topic_like ADD CONSTRAINT FK_94882106A76ED395 FOREIGN KEY (user_id) REFERENCES p5_Users (id)');
        $this->addSql('ALTER TABLE p5_topics CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE p5_articles CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE p5_articlescomments CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE p5_topicscomments CHANGE author_id author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE topic_like');
        $this->addSql('ALTER TABLE p5_Articles CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE p5_ArticlesComments CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE p5_Topics CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE p5_TopicsComments CHANGE author_id author_id INT NOT NULL');
    }
}
