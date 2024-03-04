DROP TABLE IF EXISTS cars;

CREATE TABLE cars (
    vin VARCHAR(20) UNIQUE NOT NULL,
    make VARCHAR(40) NOT NULL,
    model VARCHAR(80) NOT NULL,
    date_purchased DATE NOT NULL,
    retail_price DECIMAL UNSIGNED NOT NULL,
    PRIMARY KEY (vin)
);