DROP DATABASE klask;
CREATE DATABASE klask;
USE klask;

CREATE TABLE spheres (
    id_sphere       INT AUTO_INCREMENT PRIMARY KEY,
    name_sphere     VARCHAR(100) NOT NULL,
    color_sphere    VARCHAR(50)  NOT NULL
) ENGINE=InnoDB;

CREATE TABLE activity_categories (
    id_category      INT AUTO_INCREMENT PRIMARY KEY,
    type_category    VARCHAR(255) NOT NULL,
    time_max         INT NOT NULL,
    nbr_point        INT NOT NULL,
    nbr_max_activity  INT  NULL,
    id_sphere        INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE professions (
    id_profession          INT AUTO_INCREMENT PRIMARY KEY,
    description_profession TEXT NULL,
    code_rom               VARCHAR(5) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE classes (
    id_class    INT AUTO_INCREMENT PRIMARY KEY,
    school      VARCHAR(200) NULL,
    name_class  VARCHAR(50) NULL
) ENGINE=InnoDB;

CREATE TABLE users (
    id_user        INT AUTO_INCREMENT PRIMARY KEY,
    pseudo_user    VARCHAR(100) NOT NULL,
    id_class       INT NOT NULL,
    id_authority   INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE activities (
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

CREATE TABLE validations (
    id_validation    INT AUTO_INCREMENT PRIMARY KEY,
    time_validation  DATETIME NOT NULL,
    id_activity      INT NOT NULL,
    id_user   INT NOT NULL
) ENGINE=InnoDB;


CREATE TABLE authorities (
	id_authority INT PRIMARY KEY AUTO_INCREMENT,
	role_user     VARCHAR(50) NOT NULL,
	authority_user  VARCHAR(50) NOT NULL
) ENGINE=InnoDB;


ALTER TABLE activity_categories
ADD CONSTRAINT fk_categories_spheres
FOREIGN KEY (id_sphere) REFERENCES spheres(id_sphere)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE users
ADD CONSTRAINT fk_users_classes
FOREIGN KEY (id_class) REFERENCES classes(id_class)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activities
ADD CONSTRAINT fk_activities_categories
FOREIGN KEY (id_category) REFERENCES activity_categories(id_category)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activities
ADD CONSTRAINT fk_activities_spheres
FOREIGN KEY (id_sphere) REFERENCES spheres(id_sphere)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE activities
ADD CONSTRAINT fk_activities_professions
FOREIGN KEY (id_profession) REFERENCES professions(id_profession)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE validations
ADD CONSTRAINT fk_validations_activities
FOREIGN KEY (id_activity) REFERENCES activities(id_activity)
ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE validations
ADD CONSTRAINT fk_validations_users
FOREIGN KEY (id_user) REFERENCES users(id_user)
ON DELETE RESTRICT ON UPDATE CASCADE;



ALTER TABLE users
ADD CONSTRAINT fk_users_authorities
FOREIGN KEY (id_authority) REFERENCES authorities(id_authority)
ON DELETE RESTRICT ON UPDATE CASCADE;



ALTER TABLE spheres
ADD CONSTRAINT uq_sphere_name UNIQUE (name_sphere),
ADD CONSTRAINT uq_sphere_color UNIQUE (color_sphere);

ALTER TABLE activity_categories
ADD CONSTRAINT uq_category_type UNIQUE (type_category);

ALTER TABLE professions
ADD CONSTRAINT uq_profession_description UNIQUE (description_profession(255));


ALTER TABLE users
ADD CONSTRAINT uq_user_pseudo UNIQUE (pseudo_user);

ALTER TABLE activities
ADD CONSTRAINT uq_activities_nom UNIQUE (name_activity),
ADD CONSTRAINT uq_activity_qrcode UNIQUE (qrcode_activity),
ADD CONSTRAINT uq_activity_profession UNIQUE (id_profession);