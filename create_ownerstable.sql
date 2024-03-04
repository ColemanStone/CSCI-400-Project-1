DROP TABLE IF EXISTS owners;

CREATE TABLE owners (
    user_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(40) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(80) NOT NULL,
    pass VARCHAR(256) NOT NULL,
    registration_date DATE NOT NULL,
    PRIMARY KEY (user_id),
    UNIQUE KEY (email)
);