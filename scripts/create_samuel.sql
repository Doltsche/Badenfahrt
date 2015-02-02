# Skriptname insert_roles.sql 
#
# Ausgabe der Stati für automatisches laden mit:
#   mysql < insert_roles.sql  
# 
# Autor: Egger, Junker, Vogelbacher
# 
#START insert_roles.sql

SELECT '-- Starte mit Script --' AS ' ';

SELECT '-- Falls keine Datengefüllt werden, bitte prüfen ob es Datenbank gibt' AS ' ';

#Zeige ob es Datenbank gibt
SELECT IF(EXISTS (SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'badenfahrt'), 
	'OK: Datenbank existiert!',
	'FEHLER: Datenbank Existiert nicht!') AS ' '; 

#Wähle Datenbank
USE badenfahrt;

SELECT '-- Schreibe users --' AS ' ';

INSERT INTO user
    (identity, 
    displayName, 
    password, 
    firstname,
    lastname,
    streetAndNr,
    postalCode,
    city,
    phone) 
VALUES
    (
    'samuel.egger7@gmail.com', 
    'Samuel Egger', 
    'test123', 
    'Samuel',
    'Egger',
    'Parkstrasse 1',
    '3072',
    'Ostermundigen',
    '0794288465');

SELECT '-- Schreibe user_role_linker --' AS ' ';

INSERT INTO user_role_linker
    (user_id, role_id) 
VALUES
    (1, 1), #guest
    (1, 2); #user
SHOW WARNINGS;

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

#END insert_roles.sql
