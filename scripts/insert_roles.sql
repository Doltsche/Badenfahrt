<<<<<<< HEAD

# Skriptname insert_roles.sql 
#
# Ausgabe der Stati für automatisches laden mit:
=======
# Skriptname insert_roles.sql 
#
# Ausgabe der Stati fÃ¼r automatisches laden mit:
>>>>>>> 41235eb
#   mysql < insert_roles.sql  
# 
# Autor: Egger, Junker, Vogelbacher
# 
#START insert_roles.sql

SELECT '-- Starte mit Script --' AS ' ';

<<<<<<< HEAD
SELECT '-- Falls keine Datengefüllt werden, bitte prüfen ob es Datenbank gibt' AS ' ';
=======
SELECT '-- Falls keine DatengefÃ¼llt werden, bitte prÃ¼fen ob es Datenbank gibt' AS ' ';
>>>>>>> 41235eb

#Zeige ob es Datenbank gibt
SELECT IF(EXISTS (SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'badenfahrt'), 
	'OK: Datenbank existiert!',
	'FEHLER: Datenbank Existiert nicht!') AS ' '; 

<<<<<<< HEAD
#Wähle Datenbank
=======
#WÃ¤hle Datenbank
>>>>>>> 41235eb
USE badenfahrt;

SELECT '-- Schreibe Rollen --' AS ' ';

INSERT INTO role 
<<<<<<< HEAD
    (id, parent_id, roleId) 
VALUES
    (1, NULL, 'guest'),
    (2, 1, 'user'),
    (3, 2, 'moderator'), 
    (4, 3, 'administrator');
=======
    (id, parent_id, role_id) 
VALUES
    (1, NULL, 'guest'),
    (2, 1, 'user'),
    (3, 2, 'administrator');
>>>>>>> 41235eb
SHOW WARNINGS;

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

<<<<<<< HEAD
#END insert_roles.sql
=======
#END insert_roles.sql
>>>>>>> 41235eb
