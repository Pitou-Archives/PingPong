# PingPong

Little tool for monitoring your network applications status (UP or DOWN) - /!\ FRENCH doc/display /!\

# Documentation

Pour les commentaires explicatifs, c'est ici que �a se passe:

- app/controllers/home.php
- assets/css/style.css
- assets/js/script.js
- cron.php

Vous devez mettre en place une cron pour que cela fonctionne: explications dans cron.sh

# Base de donn�es

Editez system/Database.php pour connecter PingPong � votre propre serveur de BDD.

La structure SQL est fournie dans ping_pong.sql

# Configuration serveur

Le fichier de configuration Apache (.htaccess) est fourni. Pour les h�r�tiques barbus qui utilisent NGINX, D�brouillez-vous <3