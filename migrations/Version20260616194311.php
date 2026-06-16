<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260616194311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE activity (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              description VARCHAR(500) DEFAULT NULL,
              qrcode VARCHAR(255) DEFAULT NULL,
              qrcode_token VARCHAR(255) DEFAULT NULL,
              point_x DOUBLE PRECISION DEFAULT NULL,
              point_y DOUBLE PRECISION DEFAULT NULL,
              soft_limit INT DEFAULT 0 NOT NULL,
              hard_limit INT DEFAULT 0 NOT NULL,
              is_internship TINYINT DEFAULT 0 NOT NULL,
              is_available TINYINT DEFAULT 1 NOT NULL,
              estimated_wait_minutes INT DEFAULT NULL,
              stand_updated_at DATETIME DEFAULT NULL,
              sphere_id INT DEFAULT NULL,
              category_id INT NOT NULL,
              UNIQUE INDEX UNIQ_AC74095A5E237E06 (name),
              UNIQUE INDEX UNIQ_AC74095A329C30C7 (qrcode_token),
              INDEX IDX_AC74095A75FD4EF9 (sphere_id),
              INDEX IDX_AC74095A12469DE2 (category_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE activity_category (
              id INT AUTO_INCREMENT NOT NULL,
              type VARCHAR(50) NOT NULL,
              nbr_points INT UNSIGNED NOT NULL,
              nbr_max_activity INT NOT NULL,
              beginning_hour_category DATETIME DEFAULT NULL,
              restrictions TEXT DEFAULT NULL,
              UNIQUE INDEX UNIQ_A646A9CF8CDE5729 (type),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE app_parameter (
              id INT AUTO_INCREMENT NOT NULL,
              param_key VARCHAR(100) NOT NULL,
              param_value VARCHAR(500) NOT NULL,
              param_type VARCHAR(20) DEFAULT 'string' NOT NULL,
              description VARCHAR(500) DEFAULT NULL,
              updated_at DATETIME NOT NULL,
              UNIQUE INDEX UNIQ_82F8CE2535A9B410 (param_key),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE authority (
              id INT AUTO_INCREMENT NOT NULL,
              authority_user VARCHAR(50) NOT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE authority_role (
              authority_id INT NOT NULL,
              role_id INT NOT NULL,
              INDEX IDX_6390BF9A81EC865B (authority_id),
              INDEX IDX_6390BF9AD60322AC (role_id),
              PRIMARY KEY (authority_id, role_id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE establishment (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(50) NOT NULL,
              beginning_hour_event DATETIME DEFAULT NULL,
              end_hour_event DATETIME DEFAULT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `group` (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(50) DEFAULT NULL,
              color VARCHAR(50) NOT NULL,
              code VARCHAR(10) NOT NULL,
              establishment_id INT NOT NULL,
              event_id INT NOT NULL,
              UNIQUE INDEX UNIQ_6DC044C577153098 (code),
              INDEX IDX_6DC044C58565851 (establishment_id),
              INDEX IDX_6DC044C571F7E88B (event_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profession (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              description VARCHAR(500) DEFAULT NULL,
              code_rome VARCHAR(5) DEFAULT NULL,
              narrator VARCHAR(50) DEFAULT NULL,
              UNIQUE INDEX UNIQ_BA930D697899D1EA (code_rome),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (
              id INT AUTO_INCREMENT NOT NULL,
              name_role VARCHAR(50) NOT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE scan (
              id INT AUTO_INCREMENT NOT NULL,
              hour_validation DATETIME NOT NULL,
              activity_id INT NOT NULL,
              user_id INT NOT NULL,
              INDEX IDX_C4B3B3AE81C06096 (activity_id),
              INDEX IDX_C4B3B3AEA76ED395 (user_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sphere (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(100) NOT NULL,
              color VARCHAR(50) NOT NULL,
              icon VARCHAR(255) DEFAULT NULL,
              description LONGTEXT DEFAULT NULL,
              point_x DOUBLE PRECISION DEFAULT NULL,
              point_y DOUBLE PRECISION DEFAULT NULL,
              radius DOUBLE PRECISION NOT NULL,
              category_id INT DEFAULT NULL,
              UNIQUE INDEX UNIQ_55F966875E237E06 (name),
              UNIQUE INDEX UNIQ_55F96687665648E9 (color),
              INDEX IDX_55F9668712469DE2 (category_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
              id INT AUTO_INCREMENT NOT NULL,
              pseudo VARCHAR(255) DEFAULT NULL,
              email VARCHAR(180) DEFAULT NULL,
              password VARCHAR(255) DEFAULT NULL,
              blocked_until DATETIME DEFAULT NULL,
              poked_at DATETIME DEFAULT NULL,
              invalid_scan_count INT DEFAULT 0 NOT NULL,
              group_code VARCHAR(10) DEFAULT NULL,
              authority_id INT NOT NULL,
              group_id INT DEFAULT NULL,
              UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo),
              UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
              INDEX IDX_8D93D64981EC865B (authority_id),
              INDEX IDX_8D93D649FE54D947 (group_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_sphere_rating (
              rating INT NOT NULL,
              user_id INT NOT NULL,
              sphere_id INT NOT NULL,
              INDEX IDX_E5DF9976A76ED395 (user_id),
              INDEX IDX_E5DF997675FD4EF9 (sphere_id),
              PRIMARY KEY (user_id, sphere_id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id BIGINT AUTO_INCREMENT NOT NULL,
              body LONGTEXT NOT NULL,
              headers LONGTEXT NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL,
              available_at DATETIME NOT NULL,
              delivered_at DATETIME DEFAULT NULL,
              INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (
                queue_name, available_at, delivered_at,
                id
              ),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              activity
            ADD
              CONSTRAINT FK_AC74095A75FD4EF9 FOREIGN KEY (sphere_id) REFERENCES sphere (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              activity
            ADD
              CONSTRAINT FK_AC74095A12469DE2 FOREIGN KEY (category_id) REFERENCES activity_category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              authority_role
            ADD
              CONSTRAINT FK_6390BF9A81EC865B FOREIGN KEY (authority_id) REFERENCES authority (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              authority_role
            ADD
              CONSTRAINT FK_6390BF9AD60322AC FOREIGN KEY (role_id) REFERENCES role (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              `group`
            ADD
              CONSTRAINT FK_6DC044C58565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              `group`
            ADD
              CONSTRAINT FK_6DC044C571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              scan
            ADD
              CONSTRAINT FK_C4B3B3AE81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)
        SQL);
        $this->addSql('ALTER TABLE scan ADD CONSTRAINT FK_C4B3B3AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql(<<<'SQL'
            ALTER TABLE
              sphere
            ADD
              CONSTRAINT FK_55F9668712469DE2 FOREIGN KEY (category_id) REFERENCES activity_category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user
            ADD
              CONSTRAINT FK_8D93D64981EC865B FOREIGN KEY (authority_id) REFERENCES authority (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user
            ADD
              CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_sphere_rating
            ADD
              CONSTRAINT FK_E5DF9976A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_sphere_rating
            ADD
              CONSTRAINT FK_E5DF997675FD4EF9 FOREIGN KEY (sphere_id) REFERENCES sphere (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A75FD4EF9');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A12469DE2');
        $this->addSql('ALTER TABLE authority_role DROP FOREIGN KEY FK_6390BF9A81EC865B');
        $this->addSql('ALTER TABLE authority_role DROP FOREIGN KEY FK_6390BF9AD60322AC');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C58565851');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C571F7E88B');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AE81C06096');
        $this->addSql('ALTER TABLE scan DROP FOREIGN KEY FK_C4B3B3AEA76ED395');
        $this->addSql('ALTER TABLE sphere DROP FOREIGN KEY FK_55F9668712469DE2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64981EC865B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE54D947');
        $this->addSql('ALTER TABLE user_sphere_rating DROP FOREIGN KEY FK_E5DF9976A76ED395');
        $this->addSql('ALTER TABLE user_sphere_rating DROP FOREIGN KEY FK_E5DF997675FD4EF9');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_category');
        $this->addSql('DROP TABLE app_parameter');
        $this->addSql('DROP TABLE authority');
        $this->addSql('DROP TABLE authority_role');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE profession');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE scan');
        $this->addSql('DROP TABLE sphere');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_sphere_rating');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
