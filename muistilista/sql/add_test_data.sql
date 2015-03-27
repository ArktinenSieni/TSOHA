-- Lisää INSERT INTO lauseet tähän tiedostoon
-- Account-taulu
INSERT INTO Account (name, password) VALUES ('Matti', 'meikelainen');

-- Category-taulu
INSERT INTO Category (name, account_id) VALUES ('Koulu', 1);
INSERT INTO Category (name, account_id) VALUES ('Koti', 1);

-- Chore-taulu
INSERT INTO Chore (name, priority, account_id) VALUES ('luento', 1, 1);
INSERT INTO Chore (name, priority, account_id) VALUES ('läksyt', 3, 1);
INSERT INTO Chore (name, priority, account_id) VALUES ('banaani', 5, 1);