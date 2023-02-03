<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922052814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleves_reponses DROP FOREIGN KEY FK_328CD03BE4084792');
        $this->addSql('ALTER TABLE eleves_reponses DROP FOREIGN KEY FK_328CD03BC2140342');
        $this->addSql('DROP TABLE eleves_reponses');
        $this->addSql('DROP TABLE reponses_eleves');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleves_reponses (eleves_id INT NOT NULL, reponses_id INT NOT NULL, INDEX IDX_328CD03BC2140342 (eleves_id), INDEX IDX_328CD03BE4084792 (reponses_id), PRIMARY KEY(eleves_id, reponses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponses_eleves (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE eleves_reponses ADD CONSTRAINT FK_328CD03BE4084792 FOREIGN KEY (reponses_id) REFERENCES reponses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleves_reponses ADD CONSTRAINT FK_328CD03BC2140342 FOREIGN KEY (eleves_id) REFERENCES eleves (id) ON DELETE CASCADE');
    }
}
