
# Skriptname create_badenfahrt.sql 
#
# Ausgabe der Stati für automatisches laden mit:
#   mysql < create_badenfahrt.sql  
# 
# Autor: Egger, Junker, Vogelbacher
# 
#START create_badenfahrt.sql

SELECT '-- Starte mit Script --' AS ' ';

DROP DATABASE IF EXISTS badenfahrt;

SELECT '-- Erstelle Datenbank "badenfahrt" --' AS ' ';
CREATE DATABASE badenfahrt;

#Wähle Datenbank
USE badenfahrt;

SELECT '-- Rechtevergabe: sys_badenfahrt auf badenfahrt --' AS ' ';
GRANT ALL ON  badenfahrt.* TO 'sys_badenfahrt'@'localhost' IDENTIFIED BY 'sys_badenfahrt';

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

#END create_badenfahrt.sql