# Skriptname create_test_data.sql 
#
#Password for every user is "test123"
# Ausgabe der Stati für automatisches laden mit:
#   mysql < create_test_data.sql  
# 
# Autor: Egger, Junker, Vogelbacher
# 
#START create_testdata.sql

SELECT '-- Starte mit Script --' AS ' ';

SELECT '-- Falls keine Datengefüllt werden, bitte prüfen ob es Datenbank gibt' AS ' ';

#Zeige ob es Datenbank gibt
SELECT IF(EXISTS (SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'badenfahrt'), 
	'OK: Datenbank existiert!',
	'FEHLER: Datenbank Existiert nicht!') AS ' '; 

#Wähle Datenbank
USE badenfahrt;

SELECT '-- Lösche alle Benutzer --' AS ' ';
delete from user_role_linker;
delete from user;

SELECT '-- Schreibe Testuser --' AS ' ';

INSERT INTO user
    (id, identity, 			displayName, 	password, 			 	firstname, 	lastname, 	streetAndNr, 		postalCode, 	city, 		phone, 		state) 
VALUES
    (1,	'admin@badenfahrt.ch', 		'Admin', 	'cc03e747a6afbbcbf8be7668acfebee5', 	'Samuel', 	'Egger', 	'Parkstrasse 1', 	'3072', 	'Ostermundigen', '0794288465', 	1),
    (2,	'samuel.egger7@gmail.com', 	'Samuel Egger', 'cc03e747a6afbbcbf8be7668acfebee5', 	'Samuel', 	'Egger', 	'Parkstrasse 1', 	'3072', 	'Ostermundigen', '0794288465', 	1);

SELECT '-- Schreibe Berechtigungen --' AS ' ';

INSERT INTO user_role_linker
    (user_id, role_id) 
VALUES
    (1, 4), #administrator
    (2, 3); #user
SHOW WARNINGS;

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

#END insert_roles.sql
