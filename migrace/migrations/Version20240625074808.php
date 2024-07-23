<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625074808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Blog ADD CONSTRAINT FK_6027FE7DA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_6027FE7DA76ED395 ON Blog (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Blog DROP FOREIGN KEY FK_6027FE7DA76ED395');
        $this->addSql('DROP INDEX IDX_6027FE7DA76ED395 ON Blog');
        $this->addSql('ALTER TABLE Blog DROP user_id');
    }
}
