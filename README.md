OVE - Bundle Symfony Manuel de Procedures
=========================



## Fonctionnalités

* Fenêtre d'authentification
* Gestion des associations pour autoriser plusieurs méthodes d'authentifcation en fonction de l'association (ex : MySQL, LDAP,..)
* Gestion des utilisateurs, des rôles et de l'affactation des rôles aux utilisateurs
* Thème graphique basé sur Bootstrap 



## Installation


### Installation de composer

Composer permet de gérer les dépendances et l'installation de modules PHP

Installation : 

    cd /home/votre_login/bin
    curl -s http://getcomposer.org/installer | php

Mise à jour du PATH : 

    vim /home/tony/.profile 
    if [ -d "$HOME/bin" ] ; then
      PATH="$HOME/bin:$PATH"
    fi
    
Utilisation : 

    composer.phar

### Installation de Synfony

Installation de la dernière version 2.3 de Synfony (Synfony 2.4 necessite PHP 5.4) : 

    cd /var/www/votre_projet
    composer.phar create-project symfony/framework-standard-edition symfony 2.3.*


Ajouter votre adresse IP dans `app_dev.php` pour pouvoir accèder à Synfony : 

    vim web/app_dev.php
    || !in_array(@$_SERVER['REMOTE_ADDR'],
        array(
                '127.0.0.1', 'fe80::1', '::1',
                '192.168.1.1'
        ))



### Installation de ce bundle 

Ajouter cette ligne dans la section `require` de `composer.json` :

    cd symfony
    vim composer.json
        "require": {
            ...
            "ove/authentification-bundle": "dev-master"


Ajouter cette ligne dans la section `extra` de `composer.json` :

    vim composer.json
    "extra": {
        ...
        "symfony-assets-install": "symlink",

Installer le Bundle avec composer : 

    composer.phar update

Activer le Bundle en ajoutant cette ligne dans l'array des bundle : 

       vim app/AppKernel.php
       $bundles = array(
          ...
          new OVE\AuthentificationBundle\OVEAuthentificationBundle(),



Mise en place des fichiers de configuration : 

    cp vendor/ove/authentification-bundle/OVE/AuthentificationBundle/Resources/Docs/routing.yml app/config/
    cp vendor/ove/authentification-bundle/OVE/AuthentificationBundle/Resources/Docs/security.yml app/config/
    cp vendor/ove/authentification-bundle/OVE/AuthentificationBundle/Resources/Docs/ove_authentification.yml app/config/


Importation des paramètres : 
    
    vim app/config/config.yml 
    imports:
    ...
    - { resource: ove_authentification.yml }


Attention : Pensez à modifier les clés d'authentification et à renseigner les paramètres pour Gestetab



Mettre en place les assets : 

    php app/console assets:install web --symlink


Initialiser les tables de la base de données : 

    php app/console doctrine:schema:update --dump-sql
    php app/console doctrine:schema:update --force


Vider le cache

    app/console cache:clear


Vérfier que le routage fonctionne : 

    php app/console router:debug



## Connexion

Par défaut, la connexion se fait avec le login admin / adminpass

Il faut donc commencer par changer le mot de passe dans ce fichier : 

    app/config/security.yml

Ensuite, une fois connecté, il faut créer une association pour pouvoir se connecter via une table MySQL ou via un annuaire LDAP




## Gestion des droits

Une fois connecté, il faut créer les rôles : 
  
  * ROLE_ADMIN
  * ROLE_PARAM

Créer les utilisateurs ayant des rôles autres que `ROLE_USER` et affecter les rôles aux utilisateurs
  





