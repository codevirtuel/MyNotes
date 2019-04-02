# MyNotes
## Gestionnaire web de notes de l'IUT de Laval

### Besoins

* Serveur API - [API_ENT_Notes](https://github.com/codevirtuel/API_ENT_Notes)
* npm - [Site web de npm](https://www.npmjs.com/get-npms)
* composer - [Site web de composer](https://getcomposer.org/download/)

### Installation

#### Etape 1 - Copie des fichiers
Pour la sécurisation des données du site, il doit être possible de placer un fichier un dossier avant l'emplacement du fichier index.html.  
(ex : ./www/index.html et non pas ./index.html)

* Clonez le repository dans le dossier public de votre serveur (i.e public, www, ...)  
`git clone https://github.com/codevirtuel/MyNotes`
* Ouvrez le fichier `php/notesCatcher.php` et changez la constante `SERVER` par l'url de votre serveur API (i.e [Repository du serveur](https://github.com/codevirtuel/API_ENT_Notes)

### Etape 2 - Installation des bibliothèques

* Retournez à la racine du site web
* Executez les commandes suivantes pour installer les bibliothèques  
`npm install`  
`composer install`

### Etape 3 - Création du fichier de clé

* Entrez la commande suivante pour obtenir une clé secrète :  
`vendor/bin/generate-defuse-key`  
* Créez un fichier 'keyfile' dans le dossier parent de votre site
`cd
* Collez la clé secrète dans ce nouveau fichier


