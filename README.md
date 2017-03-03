CDG
====

Fait des statistiques à partir de calendriers ICS. Des codes (ex : [5Z]) doivent
être ajouté au titre des évènements.

Les évènements récursifs ne sont pas gérés
Les évènements d'une durée supérieur à 12h ne sont pas pris en compte.
Les évènements (avec des codes) se chevauchant ne sont pas pris en compte (le
premier évènement est pris en compte, le deuxième et les suivant sont ignorés et
remontent des erreurs. L'ordre n'est pas nécessairement chronologique)

Installation
------------

Développé et testé avec php 7.0 (devrait fonctionner avec php 5.6 et plus)
Décompresser l'archive et éxecuter "composer install" dans le dossier créé.
Donner les droits en écriture au dossier 'data'.
