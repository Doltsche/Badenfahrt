<img src="http://framework.zend.com/images/head-bottom-picture.png"
 alt="ZF2 Logo 1" title="ZF2 User Module" align="right" />


# Zend Skeleton Application - UserModul für Semeterprojekt: Badenfahrt
---



Dies ist ein UserModul für ZF2.
---
<img src="https://packages.zendframework.com/docs/latest/manual/en/_static/zf2_logo.png"
 alt="ZF2 Logo 0" title="ZF2 User Module" align="right" />

Es gibt folgende Aktionen:

  - Index
   - Anzeige der Hauptseite
  - Registrieren
   - Formular für Benutzer-Registrierung
  - Anmelden / Abmelden
   - Formulare für das Login und das Logout
  - Services
   - Self-Service Profile Bearbeiten
   - Benutzerverwaltung für Administratoren

Von [Samuel Egger], [Christoph Junker] und [Andreas Vogelbacher] für ein Semesterprojekt in der [ABB-TS] [1]:

Version
---
###### 0.9.69

> Achtung:

> Installationsaleitungen beachten


Einführung
------------
Dieses User-Modul ist im Rahmen der Semester Arbeit für die ABB-TS WS/SS 2015 unter verwendung des ZF2 MVC layer und dessen Modulsystems enstamnden.

Folgende Programme werden Benötigt:
 - GIT
 - MySQL >= 5.6
 - PHP >= 5.6.3
 - Apache >= 2.4

Folgende Module werden Verwendet:
 - Doctrine
 - Doctrine ORM
 - BjyAuthorize

Wir verwenden dabei auch folgende Bibliotheken:
 - Bootstrap
 - JQuery

Zum einsatz kommt in diesem Modul auch die Techniken:
 - Ajax
 - E-Mail versand mit Zend

Installation
------------

Projekt Klonen mit Git
--------------------
Zuerst muss das Projekt von GitHub geholt werden:

    git clone git://github.com/Dolsche/Badenfahrt

Web Server Aufsetzten
----------------

### PHP Configureieren
In der `PHP.ini` sicherstellen dass folgende Extensions geladen werden:
 - php_fileinfo.dll
 - php_gd2.dll
 - php_mysqli.dll
 - php_openssl.dll
 - php_pdo_mysql.dll

### Apache Setup

Um Apache zu konfigurieren muss ein Virtueller Host eingerichtet werden der auf das public/ Verzeichniss des Projektes zeigt. Dazu muss im Apache Verzeichniss die Datei `conf\extra\httpd-vhosts.conf` angepasst werden.
Es sollte in etwa so aussehen:


    <VirtualHost *:80>
        ServerName Badenfahrt.local
        DocumentRoot /path/to/Badenfahrt/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/Badenfahrt/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

Danch Apache neu starten.

Projektspezifische Anpassungen vornehmen
----------------------------

Im Projektverzeichniss müssen noch Dateien in \config\autoload\ erstellt werden gemäss ihrer Vorlagen für den zugriff auf die MySQL Datenbank und den Emailanbieter:
 - mail.config.local.php
 - doctrineconnection.local.php

Hierzu die Vorlagen ohne .disk kopieren und entsprechend anpassen.

ZF2 und BjyAuthorize Module herunter laden
----------------------------

Wir verwenden dem empfohlenen Weg und holen Module mit hilve vom `Composer`.

    cd pfad/zu/Badenfahrt
    composer self-update
    composer install

(Mit `self-update` stellen wir zuerst sicher das `composer` up-to-date ist)

Datenbank erstelen
----------------------------
Es muss noch die Datenbank in MySQL erstellt werden.

Hierzu kann z.B. das SQL-Script in `scripts\create_database.sql` verwendet werden.

Danenbank Schema / Tabellen erstellen
----------------------------
Um Das Schema erstellen zu könne muss temporär erst die verwendeung von BjyAuthorize aus dem entwernt werden.
> Die Gründe für diese temporäre Änderung wurden nicht weiter verfolgt, sind aber für zukünftige Versionen empfehlenswert.

Dazu folgende Änderungen durchführen:
In der Datei `config\application.config.php` das BjyAuthorize auskommentieren.

     'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        //'BjyAuthorize',
        'Application',
        'User',
        'ZendDeveloperTools'
    ),

In der Datei `module\user\module.php` die verwendung von BjyAuthorize aus der `onBootstrap`funktion auskommentieren.


    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        // Add ACL information to the Navigation view helper
      /*
        $authorize = $sm->get('BjyAuthorizeServiceAuthorize');
        $acl = $authorize->getAcl();
        $role = $authorize->getIdentity();
        \Zend\View\Helper\Navigation::setDefaultAcl($acl);
        \Zend\View\Helper\Navigation::setDefaultRole($role);
      */
    }

Nun kann mit `Doctrine`, in der Kommandozeile vom Projektbasis-Pfad, das Schema erstellt werden:

    vendor\bin\doctrine-module  orm:schema-tool:create

> Die Änderungen nach erfolgrichem Kreieren des Schemas nicht vergessen wieder rückgängig zu machen!

Tabellen füllen
----------------------------

Die Tabellen müssen mit den Rollen-Definitionen für das BjyAuthorize gefüllt werden.
Hierfür kann das SQL-Script in `scripts\insert_roles.sql` vwerwendet werden.

#Geschaft!
Nun kann die Webseite mit dem Browser Ihrere Wahl getestet werden.


[Andreas Vogelbacher]:nixda@willkeinspam.com
[Samuel Egger]:nixda@willkeinspam.com
[Christoph Junker]:nixda@willkeinspam.com
[1]:http://abbts.ch