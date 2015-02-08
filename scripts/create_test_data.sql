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
    (1,	'admin@badenfahrt.ch', 		'Admin', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Admin', 	'Badenfahrt', 	'Bruggerstrasse 50', 	'5400', 	'Baden', '058585000', 	1),
    (2,	'samuel.egger7@gmail.com', 	'Samuel Egger', 'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Samuel', 	'Egger', 	'Parkstrasse 1', 	'3072', 	'Ostermundigen', '0794288465', 	1),
    (3,	'christoph@junker.li', 	'Christoph Junker', 'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Christoph', 	'Junker', 	'Schulhausstrasse 3D', 	'5608', 	'Stetten', '080030300', 	1),
    (4,	'Andreas.Vogelbacher@gmx.net', 	'Andreas Vogelbacher', 'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Andreas', 	'Vogelbacher', 'Chäppelistrasse 67', 	'5312', 	'Döttingen', '0585858128', 	1);

INSERT INTO user
    (id, identity, 			displayName, 	password, 			 	firstname, 	lastname, 	streetAndNr, 		postalCode, 	city, 		phone, 		state) 
VALUES
    (5,	'test1@123.ch', 		'test1', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 10', 	'5400', 	'Baden', '058585000', 	1),
    (6,	'test2@123.ch', 		'test2', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 20', 	'5400', 	'Baden', '058585000', 	1),
    (7,	'test3@123.ch', 		'test3', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 30', 	'5400', 	'Baden', '058585000', 	1),
    (8,	'test4@123.ch', 		'test4', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 40', 	'5400', 	'Baden', '058585000', 	1),
    (9,	'test5@123.ch', 		'test5', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 50', 	'5400', 	'Baden', '058585000', 	1),
    (10,	'test6@123.ch', 		'test6', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 60', 	'5400', 	'Baden', '058585000', 	1),
    (11,	'test7@123.ch', 		'test7', 	'BTxbiYoGWoab512d8cgNgW9BdiowUOtjaJ3K/wQ4Y1U=', 	'Test', 	'User', 	'Gugus 70', 	'5400', 	'Baden', '058585000', 	1);
    
   

SELECT '-- Schreibe Berechtigungen --' AS ' ';

INSERT INTO user_role_linker
    (user_id, role_id) 
VALUES
    (1, 4), #administrator
    (2, 3), #user
    (3, 3), #user
    (4, 3), #user
    (5, 3), #user
    (6, 3), #user
    (7, 3), #user
    (8, 3), #user
    (9, 3), #user
    (10, 3), #user
    (11, 3); #user
SHOW WARNINGS;

#Daten speichern
COMMIT;

SELECT '-- Fertig mit Script --' AS ' ';

#END insert_roles.sql
