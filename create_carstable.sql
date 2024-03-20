DROP TABLE IF EXISTS cars;

CREATE TABLE cars (
    vin VARCHAR(20) UNIQUE NOT NULL,
    make VARCHAR(40) NOT NULL,
    model VARCHAR(80) NOT NULL,
    carYear YEAR UNSIGNED NOT NULL,
    date_purchased DATE NOT NULL,
    miles_driven INT UNSIGNED NOT NULL,
    car_condition VARCHAR(20) NOT NULL,
    PRIMARY KEY (vin)
);