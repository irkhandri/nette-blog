<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708115843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, is_liked TINYINT(1) NOT NULL, INDEX IDX_5BC96BF0A76ED395 (user_id), INDEX IDX_5BC96BF0DAE07E97 (blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0DAE07E97 FOREIGN KEY (blog_id) REFERENCES Blog (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0A76ED395');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0DAE07E97');
        $this->addSql('DROP TABLE Comment');
    }
}
