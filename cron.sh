#Accéder à votre liste de tâches planifiées (linux)
crontab -e

#Ligne à insérer dans le fichier pour que cela se lance toutes les minutes:
* * * * * cd /chemin/absolu/vers/PingPong && php cron.php insert-random-secret-key-here

#Vous pouvez modifier les * * * * * pour régler votre propre délai