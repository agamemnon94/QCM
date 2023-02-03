<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921133809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE questionnaires_questions (questionnaires_id INT NOT NULL, questions_id INT NOT NULL, INDEX IDX_BC372841CABD5540 (questionnaires_id), INDEX IDX_BC372841BCB134CE (questions_id), PRIMARY KEY(questionnaires_id, questions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questionnaires_questions ADD CONSTRAINT FK_BC372841CABD5540 FOREIGN KEY (questionnaires_id) REFERENCES questionnaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaires_questions ADD CONSTRAINT FK_BC372841BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions ADD categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D5A21214B7 ON questions (categories_id)');
        $this->addSql('ALTER TABLE reponses ADD questions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_1E512EC6BCB134CE ON reponses (questions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaires_questions DROP FOREIGN KEY FK_BC372841CABD5540');
        $this->addSql('ALTER TABLE questionnaires_questions DROP FOREIGN KEY FK_BC372841BCB134CE');
        $this->addSql('DROP TABLE questionnaires_questions');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5A21214B7');
        $this->addSql('DROP INDEX IDX_8ADC54D5A21214B7 ON questions');
        $this->addSql('ALTER TABLE questions DROP categories_id');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6BCB134CE');
        $this->addSql('DROP INDEX IDX_1E512EC6BCB134CE ON reponses');
        $this->addSql('ALTER TABLE reponses DROP questions_id');
    }
}
