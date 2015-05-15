# PingPong

Little tool for monitoring your network applications status (UP or DOWN) - /!\ FRENCH doc/display /!\

# Documentation

Pour les commentaires explicatifs, c'est ici que ça se passe:

- app/controllers/home.php
- assets/css/style.css
- assets/js/script.js
- cron.php

Vous devez mettre en place une cron pour que cela fonctionne: explications dans cron.sh

# Base de données

Editez system/Database.php pour connecter PingPong à votre propre serveur de BDD.

La structure SQL est fournie dans ping_pong.sql

# Configuration serveur

##Apache
Le fichier(.htaccess) est fourni.

##Nginx
Insérez ce code dans votre virtual host.
```
location / {
    if (!-f $request_filename){
      set $rule_1 1$rule_1;
    }
    if (!-d $request_filename){
      set $rule_1 2$rule_1;
    }
    if ($rule_1 = "21"){
      rewrite ^/(.*)$ /index.php?arg=$1 last;
    }
}
```
