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

***Images :***

<img src=https://user-images.githubusercontent.com/28647154/211823099-692ec66d-37e7-4cc6-90d8-7561270c6396.png width=500 /> <img src=https://user-images.githubusercontent.com/28647154/211822915-e59de3e8-f504-4e28-8f8d-83703828b154.png width=500 />
<img src=https://user-images.githubusercontent.com/28647154/211823177-1de3a0ea-7ec7-4662-9576-18dcc83c1408.png width=500 /> <img src=https://user-images.githubusercontent.com/28647154/211823031-50ac0ed1-6daa-4352-8d9e-52e0b736aab6.png width=500 />
<img src=https://user-images.githubusercontent.com/28647154/211822819-1e636c6b-cac1-4c0a-8edc-c2bcf398e79b.png width=500 /> <img src=https://user-images.githubusercontent.com/28647154/211825744-01d0d544-bb21-4627-8aef-1f3ef3113d43.png width=500 />
