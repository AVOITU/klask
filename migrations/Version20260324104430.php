<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260324104430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name_group VARCHAR(50) DEFAULT NULL, establishment_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_6DC044C58565851 (establishment_id), INDEX IDX_6DC044C571F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C58565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A75FD4EF9 FOREIGN KEY (sphere_id) REFERENCES sphere (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A12469DE2 FOREIGN KEY (category_id) REFERENCES activity_category (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AFDEF8996 FOREIGN KEY (profession_id) REFERENCES profession (id)');
        $this->addSql('ALTER TABLE authority_role ADD CONSTRAINT FK_6390BF9A81EC865B FOREIGN KEY (authority_id) REFERENCES authority (id)');
        $this->addSql('ALTER TABLE authority_role ADD CONSTRAINT FK_6390BF9AD60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE scan ADD CONSTRAINT FK_C4B3B3AE81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE scan ADD CONSTRAINT FK_C4B3B3AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sphere ADD CONSTRAINT FK_55F9668712469DE2 FOREIGN KEY (category_id) REFERENCES activity_category (id)');
        $this->addSql('DROP INDEX IDX_8D93D6496278D5A8 ON user');
        $this->addSql('ALTER TABLE user CHANGE classroom_id group_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64981EC865B FOREIGN KEY (authority_id) REFERENCES authority (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649FE54D947 ON user (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, name_classroom VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, establishment_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_497D309D71F7E88B (event_id), INDEX IDX_497D309D8565851 (establishment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C58565851');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C571F7E88B');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A75FD4EF9');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A12469DE2');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AFDEF8996');
        $this->addSql('ALTER TABLE authority_role DROP FOREIGN KEY FK_6390BF9A81EC865B');
        $this->addSql('ALTER TABLE authority_role DROP FOREIGN KEY FK_6390BF9AD60322AC');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE81C06096');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AEA76ED395');
        $this->addSql('ALTER TABLE sphere DROP FOREIGN KEY FK_55F9668712469DE2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64981EC865B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE54D947');
        $this->addSql('DROP INDEX IDX_8D93D649FE54D947 ON user');
        $this->addSql('ALTER TABLE user CHANGE group_id classroom_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_8D93D6496278D5A8 ON user (classroom_id)');
    }
}
