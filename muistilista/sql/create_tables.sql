-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Account (
id SERIAL PRIMARY KEY,
name varchar(12) NOT NULL,
password varchar(18) NOT NULL
);

CREATE TABLE Category(
id SERIAL PRIMARY KEY,
name varchar(20) NOT NULL,
account_id INTEGER REFERENCES Account(id)
);

CREATE TABLE Sub_category(
parent_id INTEGER REFERENCES Category(id),
child_id INTEGER REFERENCES Category(id)
);

CREATE TABLE Chore(
id SERIAL PRIMARY KEY,
name varchar(20) NOT NULL,
category_id INTEGER REFERENCES Category(id),
priority INTEGER NOT NULL,
status BOOLEAN DEFAULT FALSE,
account_id INTEGER REFERENCES Account(id)
);

