<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017062546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612CF18BB82');
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612A6CC7B2');
        $this->addSql('DROP INDEX IDX_E6D6E612A6CC7B2 ON reponses_eleves');
        $this->addSql('DROP INDEX IDX_E6D6E612CF18BB82 ON reponses_eleves');
        $this->addSql('ALTER TABLE reponses_eleves ADD examens_id INT DEFAULT NULL, ADD questions_id INT DEFAULT NULL, ADD reponses INT DEFAULT NULL, ADD success TINYINT(1) NOT NULL, DROP eleve_id, DROP reponse_id, CHANGE commentaire commentaires LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612F7AE0F1A FOREIGN KEY (examens_id) REFERENCES examens (id)');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_E6D6E612F7AE0F1A ON reponses_eleves (examens_id)');
        $this->addSql('CREATE INDEX IDX_E6D6E612BCB134CE ON reponses_eleves (questions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612F7AE0F1A');
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612BCB134CE');
        $this->addSql('DROP INDEX IDX_E6D6E612F7AE0F1A ON reponses_eleves');
        $this->addSql('DROP INDEX IDX_E6D6E612BCB134CE ON reponses_eleves');
        $this->addSql('ALTER TABLE reponses_eleves ADD eleve_id INT NOT NULL, ADD reponse_id INT NOT NULL, DROP examens_id, DROP questions_id, DROP reponses, DROP success, CHANGE commentaires commentaire LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponses (id)');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('CREATE INDEX IDX_E6D6E612A6CC7B2 ON reponses_eleves (eleve_id)');
        $this->addSql('CREATE INDEX IDX_E6D6E612CF18BB82 ON reponses_eleves (reponse_id)');
    }
}
