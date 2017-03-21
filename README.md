Nous avons eu pour but de creer une API REST nous permettant de faciliter la gestion d'une médiathéque pour cela nous avons réalisé l'API avec le framework symfony3 ainsi qu’un site servant à la démonstration de cette API ( également en symfony 3 ).

Prérequis :
avoir installer composer et avoir un wampp/xampp/lampp afin d'avoir un serveur apache.

Consigne d’installation :

Pour chacun des dossiers :

- aller dans chacun des dossiers, ouvrez un invité de commande (ou un git bash par exemple) et faire : php composer update (ou php composer.phar update selon l'invité de commande)
- pour le dossier rest_api UNIQUEMENT : 
. php bin/console doctrine:database:create (afin de créer la base de donnée, attention de bien avoir un serveur apache de lancé)
. php bin/console doctrine:schema:update --force (afin de créer nos tables)
. php bin/console doctrine:fixtures:load (pour remplir notre base de données, les valeurs et le code sont dans rest_api/src/MediathequeBundle/Datafixtures/ORM/LoadMediathequeData.php)
( Données générées pour des membres, des livres et des emprunts )
- pour le dossier demo_rest UNIQUEMENT :
.faire php bin/console assets:install 


Toute les méthodes du code sont documentées.


Documentation rest_api:

Utilisation du bundle FOSRestBundle : Ce bundle aider à créer des api RESTful plus rapidement avec Symfony, il va par exemple:
.générer des routes conforme aux standards REST.
.décoder les requêtes http toujours en respectant les standards REST.
.Gérer correction les différents codes statuts HTTP. 

Les routings générés par le bundle ne se trouve pas dans le fichier routing.yml du bundle comme d'habitude mais au-dessus des fonctions sous format @Annotations

Nous avons configuré le bundle dans config.yml afin qu'il nous retourne uniquement du JSON.

Entités Borrowing , Members et Books ( se trouvent dans MediathequeBundle/Entity ) : on va trouver dans ces fichiers, les variables et méthodes qui vont nous permettre de pouvoir travailler avec nos différents objets.
Les entités vont nous permettre de travailler avec des objets directement (et non avec notre base de données via des requêtes). Toute les méthodes sont des set et des get.
 
Nos différents contrôleurs, qui vont nous permettre de traiter nos objets pour ensuite les envoyer dans nos vues sont dans MediathequeBundle/Controller, on va retrouver 3 fichiers:
.BooksController -> méthodes qui vont traiter les différents objets Books.
.MembersController -> méthodes qui vont traiter les différents objets Members.
.BorrowingController -> méthodes qui vont traiter les différents objets Borrowing.

Pour Books et Members, la plupart des méthodes ont le même rôle récupérer, mettre à jour et supprimer.
Pour Borrowing, on a une méthode pour récupérer les livre emprunter par un membre et une autre méthode pour récupérer tout les livres qui sont empruntés.

Les deux formulaires servant pour Books et Members sont dans MediathequeBundle/Forms, ces formulaires ont été crée dans le but d'améliorer la sécurité de l'API.

Pour plus de sécurité et de cohérence, nous avons ajoutés des contraintes de validation dans nos formulaires:
fichier : MediathequeBundle/Resources/condig/validation.yml
- Le nom d'un livre est unique, le champ nom du livre et configurer pour enregistrer du texte et il ne peut être vide, tout comme le champs catégorie.
- Pour un membre, le champs nom est configurer pour enregistrer du texte et il ne peut être vide.
- Pour la table des emprunts, un livre ne peut apparaître qu'une seule et unique fois.

Les différentes url de l'API :
-Méthode GET:
.http://localhost/rest_api/web/app_dev.php/books  (récupère tous les livres)
.http://localhost/rest_api/web/app_dev.php/books/{id livre} (récupère les détails du livre dont l'id est passé en paramètre)
.http://localhost/rest_api/web/app_dev.php/members (récupère tous les membres)
.http://localhost/rest_api/web/app_dev.php/members/{id membre} (récupère les détails du membre dont l'id est passé en paramètre)
.http://localhost/rest_api/web/app_dev.php/borrowing (récupère les livres empruntés)
.http://localhost/rest_api/web/app_dev.php/members/{id membre}/books (récupère les livres empruntés pour le membre passé en paramètre)

-Méthode POST:
.http://localhost/rest_api/web/app_dev.php/books
.http://localhost/rest_api/web/app_dev.php/members

- Méthode PUT:
.http://localhost/rest_api/web/app_dev.php/books/{id livre}
.http://localhost/rest_api/web/app_dev.php/members/{id membre}

- Méthode DELETE:
http://localhost/rest_api/web/app_dev.php/books/{id livre}
http://localhost/rest_api/web/app_dev.php/members/{id membre}

Attention quelques erreurs ne sont pas gérées dans le site de démonstration. Il faut :
-Prendre en compte le fait que le nom d'un livre doit être unique (que ce soit pour les méthodes POST et PUT)
-Impossibilité de DELETE un utilisateur ou un livre qui est dans la table Borrowing 

documentation demo_rest:

C'est un site de démonstration simple pour montrer que l'API que nous avons fait fonctionne correctement.

Nous utilisons le bundle GuzzleHttp, c'est un client HTTP qui va nous permettre d'appeler facilement nos web services. Ce bundle utilise le module cURL.
GuzzleHttp est configuré pour accepter du json, la configuration est dans config.yml. Nous avons configuré une url de base afin de gagné du temps lors de l'appel des urls dans notre contrôleur.

Les différents routes utilisé par le site sont dans DemoBundle/Resources/config/routing.yml
Le contrôleur par défaut est dans DemoBundle/Controller 
Les vues sont dans DemoBundle/Views/Default

Pour lancer le site http://localhost/demo_rest/web/app_dev.php/

Thomas Caron
Alexandre Gambart


