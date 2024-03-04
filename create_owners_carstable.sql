DROP TABLE IF EXISTS owners_cars;

CREATE TABLE owners_cars (
    UID varchar(40) NOT NULL,
    VID varchar(20) NOT NULL,
    current_price DECIMAL UNSIGNED NOT NULL,
    PRIMARY KEY(UID, VID),
    FOREIGN KEY (UID) REFERENCES owners(user_id),
    FOREIGN KEY (VID) REFERENCES cars(vin)
);