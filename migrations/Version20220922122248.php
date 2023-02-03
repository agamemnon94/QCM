<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922122248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE examens (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, questionnaire_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B2E32DD7A6CC7B2 (eleve_id), INDEX IDX_B2E32DD7CE07E8FF (questionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE examens ADD CONSTRAINT FK_B2E32DD7A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('ALTER TABLE examens ADD CONSTRAINT FK_B2E32DD7CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaires (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE examens DROP FOREIGN KEY FK_B2E32DD7A6CC7B2');
        $this->addSql('ALTER TABLE examens DROP FOREIGN KEY FK_B2E32DD7CE07E8FF');
        $this->addSql('DROP TABLE examens');
    }
}
