
# Skriptname create_badenfahrt.sql 
#
<<<<<<< HEAD
# Ausgabe der Stati f�r automatisches laden mit:
=======
# Ausgabe der Stati für automatisches laden mit:
>>>>>>> 41235eb
#   mysql < create_badenfahrt.sql  
# 
# Autor: Egger, Junker, Vogelbacher
# 
#START create_badenfahrt.sql

SELECT '-- Starte mit Script --' AS ' ';

DROP DATABASE IF EXISTS badenfahrt;

SELECT '-- Erstelle Datenbank "badenfahrt" --' AS ' ';
CREATE DATABASE badenfahrt;

<<<<<<< HEAD
#W�hle Datenbank
=======
#Wähle Datenbank
>>>>>>> 41235eb
USE badenfahrt;

SELECT '-- Rechtevergabe: sys_badenfahrt auf badenfahrt --' AS ' ';
GRANT ALL ON  badenfahrt.* TO 'sys_badenfahrt'@'localhost' IDENTIFIED BY 'sys_badenfahrt';

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

<<<<<<< HEAD
#END create_badenfahrt.sql
=======
#END create_badenfahrt.sql
>>>>>>> 41235eb
