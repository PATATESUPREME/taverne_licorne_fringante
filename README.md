Taverne de la licorne fringante
===============================

Un projet Symfony de gestion de restaurant créé le 31 Janvier 2017 par Kévin DESSIMOULIE et Quentin OLIVIER.

Score SensioLabsInsight: https://insight.sensiolabs.com/projects/22d17812-923f-450a-9cbf-f01582579c88/analyses/4

Problèmes rencontrés et leurs solutions
---------------------------------------
* [Problème] Pour la gestion des boutons de validation pour l'éditeur:
    * [Solution] Implémentation d'une option pour les ButtonType mais les FormEvents ne sont pas compatibles avec ceux-ci.
    * [Solution] Implémentation de l'autorisation dans les twigs consernés.
* [Problème] Pas de serveur de mail:
* [Problème] Cron de vérification des menus ayant aucun plat:
    * [Solution] Commande symfony qui va appeler un service qui envoie un des menus.
* [Problème] Plats des menus non enregistrés:
    * [Solution] Inverser les options inversedBy et mappedBy
