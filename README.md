# Zoo Arcadia

Bienvenue sur le dépôt GitHub du projet Zoo Arcadia, une application web développée pour la gestion et la visualisation des informations des animaux d'un zoo.

## Description du Projet

Zoo Arcadia permet aux visiteurs de découvrir les animaux et leurs habitats, et offre une interface de gestion pour les employés et les vétérinaires du zoo.

## Technologies Utilisées

- **HTML, CSS, JavaScript** : Pour l'interface utilisateur.
- **PHP** : Scripting côté serveur.
- **MySQL** : Base de données.
- **Bootstrap** : Design responsive.
- **jQuery** : Scripting côté client.

## Environnement de Développement

- **XAMPP** : Serveur local Apache et MySQL.
- **Visual Studio Code, Cursor** : Éditeurs de code.
- **Composer** : Gestionnaire de dépendances PHP.
- **Git** : Contrôle de version.

## Structure du Projet

- **/admin, /vet, /employee** : Interfaces selon le rôle.
- **/template** : Éléments réutilisables.
- **/uploads** : Stockage des images.

## Installation

1. Clonez le dépôt : `git clone https://github.com/AbduUSDI/zoo_arcadia`.
2. Configurez XAMPP après l'avoir installé sur le site officiel.
3. Après avoir configuré XAMPP, vous avez besoin de créer la variable d'environnement dans vos réglages Windows
4. Une fois fait, il faut aller sur l'application XAMPP Control Panel et l'executer, le logiciel affichera alors plusieurs logiciels à ouvrir, ouvrez Apache (le serveur) ensuite ouvrez MySQL. Cliquez ensuite sur "Admin" sur la ligne MySQL.
5. La page http://localhost/phpmyadmin/ s'ouvrira sur votre navigateur par défaut, créer une base de donnée et nommer la "zoo_arcadia" vous pourrez ensuite cliquez sur "Importer".
6. Importez la base de données MySQL en utilisant le fichier zoo_arcadia.sql qui contient tout le SQL pour créer la BDD complète contenant ses tables et ses valeurs.
7. Vérifiez que la base de donnée contient bien les tables du projet.
8. Télécharger le code source du projet en .zip et décompresser le tout dans un dossier nommé "zoo_arcadia" qui devra être dans votre répertoire "htdocs" qui se trouve dans le dossier "xampp" (tout dépend de où vous l'avez positionner pendant votre installation, si par défaut : le dossier se trouve dans "utilisateur" dans le Disque local).
9. Vous pouvez maintenant lancez l'application via votre serveur local en utilisant l'url : http://localhost/zoo_arcadia/index.php sur votre navigateur par défaut.

## Utilisation

Naviguez dans l'application en utilisant les différents rôles pour explorer les fonctionnalités spécifiques à chaque utilisateur.

## Contact

Pour des questions ou suggestions, contactez le développeur via GitHub.

Nous espérons que vous trouverez ce projet utile pour comprendre la gestion d'un zoo via une application web.
