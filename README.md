Taverne de la licorne fringante
===============================

Un projet Symfony créé le 31 Janvier 2017 par Kévin DESSIMOULIE et Quentin OLIVIER.

Problèmes rencontrés et leurs solutions
---------------------------------------
* Pour la gestion des boutons de validation pour l'éditeur:
    * Implémentation d'une option pour les ButtonType mais les FormEvents ne sont pas compatibles avec ceux-ci.
    * Donc implémentation de l'autorisation dans les twigs consernés.
* Envoi de mail:
    * Pas de serveur de mail
