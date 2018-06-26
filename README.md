Outil de réservation de salle
========================
Ce fichier a pour but d'expliquer les différentes étapes d'installation
pour tester le projet.

Pré-requis
--------------

  * avoir un serveur Apache d'installer sur sa machine;
  
  * avoir le SGBD PostgreSQL d'installer sur sa machine

Comment le faire fonctionner
--------------

Afin de pouvoir tester le projet veuillez installer les modules suivants:
  * Installer Php 7.0;

  * Installer le driver pdo_pgsql;

  * Créer une base de données sur Postgres nommée 'bdd_cnam';

  * Créer un utilisateur 'dylan' avec le mot de passe 'root'. Cet utilisateur doit avoir tous les droits sur la base créée précédemment;

  * Créer les tables de la base à l'aide du fichier BDD_SCRIPT.sql;
  
  * Importer les données présentent dans le fichier databaseSQL;
  
  * Déposer le dossier de l'application "Reservation" sur votre localhost;
  
  * Faites attention à ce que l'owner des fichiers présent sous le dossier "Reservation" soit 'www-data'.
  
  L'application devrait être opérationnelle.
  
  Les comptes déjà créés:
  ----------------------
  
  * Le compte admin admin pour avoir les droits administrateurs sur le logiciel;
  
  * Le compte user user pour avoir une connection simple.
