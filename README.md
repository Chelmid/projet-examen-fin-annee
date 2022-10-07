# projet-examen-fin-annee

Projet de fin d'année en alternance.
Sujet : reproduction Site e-commerce BtoB ( avec une posiblilité de personnalisation sur un produit )
Clone site e-commerce de l'entreprise Xoopar ou j'avais fait mon alternance.

Les languages et autres utilisés
* PHP ( Symfony )
* Boostrap
* Faker ( generateur de fausse données )
* Generator d'images
* MySQL

Fonctionnement 
partie client
* login / connexion / déconnexion
* parcourir liste de produits
* sélectionnner un produit simple
* sélectionner un produit avec une personnalisation
* ajoute / supprimer le(s) produit(s) dans le panier
* ajouter un quantité
* sélectionner une couleur
* message d'information

partie admin
* login / connexion / déconnexion
* ajouter / suipprimer un produit
* ajouter / suipprimer un utilisateur

Pour démarrer le projet, il faut install la database

> php bin/console doctrine:database:create

Pour la migration
> php bin/console make:migration

> php bin/console doctrine:migrations:migrate

Pour une mise à jour de la database
> php bin/console doctrine:schema:update --force

Pour démarrer le server
> symfony server:start


# autres site fait

* Iné : site de vitrine de presentation d'un produit : https://www.ineparis.fr/
* site e-commerce BtoC : https://xoopar-shop.com/
