<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625104720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Interest ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Interest ADD CONSTRAINT FK_95487831A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_95487831A76ED395 ON Interest (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Interest DROP FOREIGN KEY FK_95487831A76ED395');
        $this->addSql('DROP INDEX IDX_95487831A76ED395 ON Interest');
        $this->addSql('ALTER TABLE Interest DROP user_id');
    }
}
