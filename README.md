Réponse au test
========================
Préambule
--------------
La question de l'**envoi d'un mail pour les panier abandonnés à J+1** est, pour moi, la question la plus ouverte du test.
Je vous fait part de mes réflexions :
J'ai opté pour enregister la date du dernier ajout dans le panier.
Cette date servira a déterminer si on est à J+1, s'entend le jour suivant et non h+24

Afin de déclencher l'envoi des emails, plusieurs solutions :

1- **PRÉFÉRÉE** : Définir une tâche CRON sur le serveur à 9h du matin par exemple qui déclenchera un script php.
**Nécessite CRON sur serveur!**

L'envoi d'un email à J+1 pour les paniers abandonnés s'effectue avec la commande

	bin/console app:execute

exemple de tâche CRON à ajouter

	0 9 * * * /path/to/php /path/to/bin/console app:execute

2- Passer par l'API de MailChimp par exemple.

3- Timer externe qui déclenche l'envoi d'une requête à la façon d'une API

4- Attendre qu'une personne passe!
Au premier abord, la solution semble hasardeuse mais en considérant que le site à au moins une visite par jour, il est possible de lancer notre tache sans maîtrise de son heure d'exécution.

5- Faire tourner un script qui simule CRON au cas où ce dernier n'est pas présent sur le serveur.

Installation
============
Pas de migrations
``` bash
composer install

bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load
bin/console assets:install
```
