<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921131303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleves_questionnaires (eleves_id INT NOT NULL, questionnaires_id INT NOT NULL, INDEX IDX_E601ED04C2140342 (eleves_id), INDEX IDX_E601ED04CABD5540 (questionnaires_id), PRIMARY KEY(eleves_id, questionnaires_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleves_questionnaires ADD CONSTRAINT FK_E601ED04C2140342 FOREIGN KEY (eleves_id) REFERENCES eleves (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleves_questionnaires ADD CONSTRAINT FK_E601ED04CABD5540 FOREIGN KEY (questionnaires_id) REFERENCES questionnaires (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleves_questionnaires DROP FOREIGN KEY FK_E601ED04C2140342');
        $this->addSql('ALTER TABLE eleves_questionnaires DROP FOREIGN KEY FK_E601ED04CABD5540');
        $this->addSql('DROP TABLE eleves_questionnaires');
    }
}
