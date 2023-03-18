<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314174410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_3AF346685E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleves (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, email VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_383B09B1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleves_classes (eleves_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_68D087BC2140342 (eleves_id), INDEX IDX_68D087B9E225B24 (classes_id), PRIMARY KEY(eleves_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examens (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, questionnaire_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B2E32DD7A6CC7B2 (eleve_id), INDEX IDX_B2E32DD7CE07E8FF (questionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaires (id INT AUTO_INCREMENT NOT NULL, form_code VARCHAR(15) NOT NULL, consigne LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_5D60D73FE334348F (form_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaires_classes (questionnaires_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_8C5BE782CABD5540 (questionnaires_id), INDEX IDX_8C5BE7829E225B24 (classes_id), PRIMARY KEY(questionnaires_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaires_questions (questionnaires_id INT NOT NULL, questions_id INT NOT NULL, INDEX IDX_BC372841CABD5540 (questionnaires_id), INDEX IDX_BC372841BCB134CE (questions_id), PRIMARY KEY(questionnaires_id, questions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, categories_id INT NOT NULL, type VARCHAR(45) NOT NULL, text LONGTEXT NOT NULL, img VARCHAR(45) DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_8ADC54D5A21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponses (id INT AUTO_INCREMENT NOT NULL, questions_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, success TINYINT(1) NOT NULL, INDEX IDX_1E512EC6BCB134CE (questions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponses_eleves (id INT AUTO_INCREMENT NOT NULL, examens_id INT DEFAULT NULL, questions_id INT DEFAULT NULL, commentaires LONGTEXT DEFAULT NULL, reponses INT DEFAULT NULL, success TINYINT(1) NOT NULL, INDEX IDX_E6D6E612F7AE0F1A (examens_id), INDEX IDX_E6D6E612BCB134CE (questions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleves_classes ADD CONSTRAINT FK_68D087BC2140342 FOREIGN KEY (eleves_id) REFERENCES eleves (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleves_classes ADD CONSTRAINT FK_68D087B9E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE examens ADD CONSTRAINT FK_B2E32DD7A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleves (id)');
        $this->addSql('ALTER TABLE examens ADD CONSTRAINT FK_B2E32DD7CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaires (id)');
        $this->addSql('ALTER TABLE questionnaires_classes ADD CONSTRAINT FK_8C5BE782CABD5540 FOREIGN KEY (questionnaires_id) REFERENCES questionnaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaires_classes ADD CONSTRAINT FK_8C5BE7829E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaires_questions ADD CONSTRAINT FK_BC372841CABD5540 FOREIGN KEY (questionnaires_id) REFERENCES questionnaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaires_questions ADD CONSTRAINT FK_BC372841BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612F7AE0F1A FOREIGN KEY (examens_id) REFERENCES examens (id)');
        $this->addSql('ALTER TABLE reponses_eleves ADD CONSTRAINT FK_E6D6E612BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleves_classes DROP FOREIGN KEY FK_68D087BC2140342');
        $this->addSql('ALTER TABLE eleves_classes DROP FOREIGN KEY FK_68D087B9E225B24');
        $this->addSql('ALTER TABLE examens DROP FOREIGN KEY FK_B2E32DD7A6CC7B2');
        $this->addSql('ALTER TABLE examens DROP FOREIGN KEY FK_B2E32DD7CE07E8FF');
        $this->addSql('ALTER TABLE questionnaires_classes DROP FOREIGN KEY FK_8C5BE782CABD5540');
        $this->addSql('ALTER TABLE questionnaires_classes DROP FOREIGN KEY FK_8C5BE7829E225B24');
        $this->addSql('ALTER TABLE questionnaires_questions DROP FOREIGN KEY FK_BC372841CABD5540');
        $this->addSql('ALTER TABLE questionnaires_questions DROP FOREIGN KEY FK_BC372841BCB134CE');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5A21214B7');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6BCB134CE');
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612F7AE0F1A');
        $this->addSql('ALTER TABLE reponses_eleves DROP FOREIGN KEY FK_E6D6E612BCB134CE');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE eleves');
        $this->addSql('DROP TABLE eleves_classes');
        $this->addSql('DROP TABLE examens');
        $this->addSql('DROP TABLE questionnaires');
        $this->addSql('DROP TABLE questionnaires_classes');
        $this->addSql('DROP TABLE questionnaires_questions');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE reponses');
        $this->addSql('DROP TABLE reponses_eleves');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
