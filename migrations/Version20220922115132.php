<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922115132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponses_eleves (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, reponse_id INT NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_E6D6E612A6CC7B2 (eleve_id), INDEX IDX_E6D6E612CF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponses (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612A6CC7B2');
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612CF18BB82');
        $this->addSql('DROP TABLE reponses_eleves');
    }
}
