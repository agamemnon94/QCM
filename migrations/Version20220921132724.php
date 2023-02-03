<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921132724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE questionnaires_classes (questionnaires_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_8C5BE782CABD5540 (questionnaires_id), INDEX IDX_8C5BE7829E225B24 (classes_id), PRIMARY KEY(questionnaires_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questionnaires_classes ADD CONSTRAINT FK_8C5BE782CABD5540 FOREIGN KEY (questionnaires_id) REFERENCES questionnaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaires_classes ADD CONSTRAINT FK_8C5BE7829E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaires_classes DROP FOREIGN KEY FK_8C5BE782CABD5540');
        $this->addSql('ALTER TABLE questionnaires_classes DROP FOREIGN KEY FK_8C5BE7829E225B24');
        $this->addSql('DROP TABLE questionnaires_classes');
    }
}
