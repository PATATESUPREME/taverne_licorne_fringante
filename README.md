Taverne de la licorne fringante
===============================

Un projet Symfony créé le 31 Janvier 2017 par Kévin DESSIMOULIE et Quentin OLIVIER.

Problèmes rencontrés et leurs solutions
---------------------------------------
* [Problème] Pour la gestion des boutons de validation pour l'éditeur:
    * [Solution] Implémentation d'une option pour les ButtonType mais les FormEvents ne sont pas compatibles avec ceux-ci.
    * [Solution] Donc implémentation de l'autorisation dans les twigs consernés.
* [Problème] Envoi de mail:
    * Pas de serveur de mail
* [Problème] Cron de vérification des menus ayant aucun plat:
    * [Solution] Commande symfony qui va appeler un service qui envoie un des menus
