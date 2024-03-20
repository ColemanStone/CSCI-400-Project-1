DROP TABLE IF EXISTS owners_cars;

CREATE TABLE owners_cars (
    owner_ID INTEGER UNSIGNED NOT NULL,
    VID varchar(20) NOT NULL,
    private_price DECIMAL UNSIGNED NOT NULL,
    retail_price DECIMAL UNSIGNED NOT NULL,
    pre_owned_price DECIMAL UNSIGNED NOT NULL,
    PRIMARY KEY(owner_ID, VID),
    FOREIGN KEY (owner_ID) REFERENCES owners(user_id),
    FOREIGN KEY (VID) REFERENCES cars(vin)
);