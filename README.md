OVE - Bundle Symfony Manuel de Procedures
=========================



## Fonctionnalités

* Manuel de procedures


## Installation

* Il faut commencer par installer Symfony et le module d'authentification comme expliqué ici : 
    https://github.com/tonygalmiche/AuthentificationBundle


Ajout des dépendances : 
    vim composer.json

    "require": {
         ...
        "egeloen/ckeditor-bundle": "~2.0",
        "punkave/symfony2-file-uploader-bundle": "dev-master",
        "ove/authentification-bundle": "dev-master",
        "ove/procedures-bundle": "dev-master"

Installation : 
    composer.phar update


Activation des nouveaux modules : 
    vim app/AppKernel.php
        $bundles = array(
           ...
           new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
           new PunkAve\FileUploaderBundle\PunkAveFileUploaderBundle(),
           new OVE\ProceduresBundle\OVEProceduresBundle(),

Mise en place de la configuration : 
    cp vendor/ove/procedures-bundle/OVE/ProceduresBundle/Resources/doc/ove_procedures.yml app/config/
    vim app/config/config.yml
    imports:
        ...
        - { resource: ove_procedures.yml }

Mise à jour de la base de données : 
    php app/console doctrine:schema:update --dump-sql
    php app/console doctrine:schema:update --force


Mise en place du routage : 
    vim app/config/routing.yml 
    ove_procedures:
        resource: "@OVEProceduresBundle/Resources/config/routing.yml"
        prefix:   /

Vérification du routage : 
    php app/console router:debug

Création des répertoire d'upload : 
    mkdir web/uploads
    mkdir web/uploads/manuel
    chown -R tony.galmiche:www-data web/uploads
    chmod -R 770 web/uploads


Vider le cache
    app/console cache:clear





