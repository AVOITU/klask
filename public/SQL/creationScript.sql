DROP DATABASE klask;
CREATE DATABASE klask;
USE klask;

CREATE TABLE sphere (
                         id_sphere       INT AUTO_INCREMENT PRIMARY KEY,
                         name_sphere     VARCHAR(100) NOT NULL,
                         color_sphere    VARCHAR(50)  NOT NULL
) ENGINE=InnoDB;

CREATE TABLE activity_category (
                                     id_category      INT AUTO_INCREMENT PRIMARY KEY,
                                     type_category    VARCHAR(255) NOT NULL,
                                     time_max         INT NOT NULL,
                                     nbr_point        INT NOT NULL,
                                     nbr_max_activity  INT  NULL,
                                     id_sphere        INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE profession (
                             id_profession          INT AUTO_INCREMENT PRIMARY KEY,
                             description_profession TEXT NULL,
                             code_rom               VARCHAR(5) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE classroom (
                         id_class    INT AUTO_INCREMENT PRIMARY KEY,
                         school      VARCHAR(200) NULL,
                         name_class  VARCHAR(50) NULL
) ENGINE=InnoDB;

CREATE TABLE user (
                       id_user        INT AUTO_INCREMENT PRIMARY KEY,
                       pseudo_user    VARCHAR(100) NOT NULL,
                       id_class       INT NOT NULL,
                       id_authority   INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE activity (
                            id_activity          INT AUTO_INCREMENT PRIMARY KEY,
                            name_activity        VARCHAR(255) NOT NULL,
                            description_activity TEXT NULL,
                            qrcode_activity      VARCHAR(255) NOT NULL,
                            point_x              INT NOT NULL,
                            point_y              INT NOT NULL,
                            id_category          INT NOT NULL,
                            id_sphere            INT NOT NULL,
                            id_profession        INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE scan (
                             id_scan    INT AUTO_INCREMENT PRIMARY KEY,
                             time_scan  DATETIME NOT NULL,
                             id_activity      INT NOT NULL,
                             id_user   INT NOT NULL
) ENGINE=InnoDB;


CREATE TABLE authority (
                             id_authority INT PRIMARY KEY AUTO_INCREMENT,
                             role_user     VARCHAR(50) NOT NULL,
                             authority_user  VARCHAR(50) NOT NULL
) ENGINE=InnoDB;


ALTER TABLE activity_category
    ADD CONSTRAINT fk_categories_sphere
        FOREIGN KEY (id_sphere) REFERENCES sphere(id_sphere)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE user
    ADD CONSTRAINT fk_user_classroom
        FOREIGN KEY (id_class) REFERENCES classroom(id_class)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activity
    ADD CONSTRAINT fk_activity_categories
        FOREIGN KEY (id_category) REFERENCES activity_category(id_category)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activity
    ADD CONSTRAINT fk_activity_sphere
        FOREIGN KEY (id_sphere) REFERENCES sphere(id_sphere)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activity
    ADD CONSTRAINT fk_activity_profession
        FOREIGN KEY (id_profession) REFERENCES profession(id_profession)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE scan
    ADD CONSTRAINT fk_scan_activity
        FOREIGN KEY (id_activity) REFERENCES activity(id_activity)
            ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE scan
    ADD CONSTRAINT fk_scan_user
        FOREIGN KEY (id_user) REFERENCES user(id_user)
            ON DELETE RESTRICT ON UPDATE CASCADE;



ALTER TABLE user
    ADD CONSTRAINT fk_user_authority
        FOREIGN KEY (id_authority) REFERENCES authority(id_authority)
            ON DELETE RESTRICT ON UPDATE CASCADE;



ALTER TABLE sphere
    ADD CONSTRAINT uq_sphere_name UNIQUE (name_sphere),
    ADD CONSTRAINT uq_sphere_color UNIQUE (color_sphere);

ALTER TABLE activity_category
    ADD CONSTRAINT uq_category_type UNIQUE (type_category);

ALTER TABLE profession
    ADD CONSTRAINT uq_profession_description UNIQUE (description_profession(255));


ALTER TABLE user
    ADD CONSTRAINT uq_user_pseudo UNIQUE (pseudo_user);

ALTER TABLE activity
    ADD CONSTRAINT uq_activity_nom UNIQUE (name_activity),
    ADD CONSTRAINT uq_activity_qrcode UNIQUE (qrcode_activity),
    ADD CONSTRAINT uq_activity_profession UNIQUE (id_profession);
