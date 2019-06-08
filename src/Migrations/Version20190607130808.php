<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607130808 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE p5_Topics (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, report INT DEFAULT NULL, INDEX IDX_2982F19012469DE2 (category_id), INDEX IDX_2982F190F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_Users (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, report INT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_DD9103DE3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_Articles (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, author_id INT DEFAULT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_951DA89312469DE2 (category_id), INDEX IDX_951DA893F675F31B (author_id), UNIQUE INDEX UNIQ_951DA8933DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_TopicsComments (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, report INT DEFAULT NULL, INDEX IDX_D4C889461F55203D (topic_id), INDEX IDX_D4C88946F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_comment_report (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_A807A31EF8697D13 (comment_id), INDEX IDX_A807A31EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_ArticlesCategories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_ArticlesComments (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, report INT DEFAULT NULL, INDEX IDX_D50D63427294869C (article_id), INDEX IDX_D50D6342F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_ForumCategories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE p5_Tips (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7AAAF07BF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_comment_report (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_68CD280EF8697D13 (comment_id), INDEX IDX_68CD280EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_like (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_948821061F55203D (topic_id), INDEX IDX_94882106A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_report (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_6728445C1F55203D (topic_id), INDEX IDX_6728445CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE p5_Tips ADD CONSTRAINT FK_7AAAF07BF675F31B FOREIGN KEY (author_id) REFERENCES p5_Users (id)');
        $this->addSql('ALTER TABLE p5_Topics ADD CONSTRAINT FK_2982F190F675F31B FOREIGN KEY (author_id) REFERENCES p5_Users (id)');
        $this->addSql('ALTER TABLE p5_Articles ADD CONSTRAINT FK_951DA893F675F31B FOREIGN KEY (author_id) REFERENCES p5_Users (id)');
        $this->addSql('ALTER TABLE p5_ArticlesComments ADD CONSTRAINT FK_D50D6342F675F31B FOREIGN KEY (author_id) REFERENCES p5_Users (id)');
        $this->addSql('ALTER TABLE p5_TopicsComments ADD CONSTRAINT FK_D4C88946F675F31B FOREIGN KEY (author_id) REFERENCES p5_Users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p5_TopicsComments DROP FOREIGN KEY FK_D4C889461F55203D');
        $this->addSql('ALTER TABLE topic_like DROP FOREIGN KEY FK_948821061F55203D');
        $this->addSql('ALTER TABLE topic_report DROP FOREIGN KEY FK_6728445C1F55203D');
        $this->addSql('ALTER TABLE p5_Topics DROP FOREIGN KEY FK_2982F190F675F31B');
        $this->addSql('ALTER TABLE p5_Articles DROP FOREIGN KEY FK_951DA893F675F31B');
        $this->addSql('ALTER TABLE p5_TopicsComments DROP FOREIGN KEY FK_D4C88946F675F31B');
        $this->addSql('ALTER TABLE article_comment_report DROP FOREIGN KEY FK_A807A31EA76ED395');
        $this->addSql('ALTER TABLE p5_ArticlesComments DROP FOREIGN KEY FK_D50D6342F675F31B');
        $this->addSql('ALTER TABLE p5_Tips DROP FOREIGN KEY FK_7AAAF07BF675F31B');
        $this->addSql('ALTER TABLE topic_comment_report DROP FOREIGN KEY FK_68CD280EA76ED395');
        $this->addSql('ALTER TABLE topic_like DROP FOREIGN KEY FK_94882106A76ED395');
        $this->addSql('ALTER TABLE topic_report DROP FOREIGN KEY FK_6728445CA76ED395');
        $this->addSql('ALTER TABLE p5_ArticlesComments DROP FOREIGN KEY FK_D50D63427294869C');
        $this->addSql('ALTER TABLE topic_comment_report DROP FOREIGN KEY FK_68CD280EF8697D13');
        $this->addSql('ALTER TABLE p5_Articles DROP FOREIGN KEY FK_951DA89312469DE2');
        $this->addSql('ALTER TABLE article_comment_report DROP FOREIGN KEY FK_A807A31EF8697D13');
        $this->addSql('ALTER TABLE p5_Topics DROP FOREIGN KEY FK_2982F19012469DE2');
        $this->addSql('ALTER TABLE p5_Articles DROP FOREIGN KEY FK_951DA8933DA5256D');
        $this->addSql('ALTER TABLE p5_Users DROP FOREIGN KEY FK_DD9103DE3DA5256D');
        $this->addSql('DROP TABLE p5_Topics');
        $this->addSql('DROP TABLE p5_Users');
        $this->addSql('DROP TABLE p5_Articles');
        $this->addSql('DROP TABLE p5_TopicsComments');
        $this->addSql('DROP TABLE article_comment_report');
        $this->addSql('DROP TABLE p5_ArticlesCategories');
        $this->addSql('DROP TABLE p5_ArticlesComments');
        $this->addSql('DROP TABLE p5_ForumCategories');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_user');
        $this->addSql('DROP TABLE p5_Tips');
        $this->addSql('DROP TABLE topic_comment_report');
        $this->addSql('DROP TABLE topic_like');
        $this->addSql('DROP TABLE topic_report');
    }
}
