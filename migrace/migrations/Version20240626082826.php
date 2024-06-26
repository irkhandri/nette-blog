<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626082826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Message ADD reciever_id INT DEFAULT NULL, ADD sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Message ADD CONSTRAINT FK_790009E35D5C928D FOREIGN KEY (reciever_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Message ADD CONSTRAINT FK_790009E3F624B39D FOREIGN KEY (sender_id) REFERENCES User (id)');
        $this->addSql('CREATE INDEX IDX_790009E35D5C928D ON Message (reciever_id)');
        $this->addSql('CREATE INDEX IDX_790009E3F624B39D ON Message (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Message DROP FOREIGN KEY FK_790009E35D5C928D');
        $this->addSql('ALTER TABLE Message DROP FOREIGN KEY FK_790009E3F624B39D');
        $this->addSql('DROP INDEX IDX_790009E35D5C928D ON Message');
        $this->addSql('DROP INDEX IDX_790009E3F624B39D ON Message');
        $this->addSql('ALTER TABLE Message DROP reciever_id, DROP sender_id');
    }
}
