<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921132051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleves_classes (eleves_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_68D087BC2140342 (eleves_id), INDEX IDX_68D087B9E225B24 (classes_id), PRIMARY KEY(eleves_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleves_classes ADD CONSTRAINT FK_68D087BC2140342 FOREIGN KEY (eleves_id) REFERENCES eleves (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleves_classes ADD CONSTRAINT FK_68D087B9E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleves_classes DROP FOREIGN KEY FK_68D087BC2140342');
        $this->addSql('ALTER TABLE eleves_classes DROP FOREIGN KEY FK_68D087B9E225B24');
        $this->addSql('DROP TABLE eleves_classes');
    }
}
