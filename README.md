<div align="center" id="top"> 
  <img src="./.github/app.gif" alt="Matchjob" />

  &#xa0;

  <!-- <a href="https://matchjob.netlify.app">Demo</a> -->
</div>

<h1 align="center">Matchjob</h1>

<p align="center">
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/Noumaa/matchjob?color=56BEB8">

  <img alt="Github language count" src="https://img.shields.io/github/languages/count/Noumaa/matchjob?color=56BEB8">

  <img alt="Repository size" src="https://img.shields.io/github/repo-size/Noumaa/matchjob?color=56BEB8">

  <img alt="License" src="https://img.shields.io/github/license/Noumaa/matchjob?color=56BEB8">

  <!-- <img alt="Github issues" src="https://img.shields.io/github/issues/Noumaa/matchjob?color=56BEB8" /> -->

  <!-- <img alt="Github forks" src="https://img.shields.io/github/forks/Noumaa/matchjob?color=56BEB8" /> -->

  <!-- <img alt="Github stars" src="https://img.shields.io/github/stars/Noumaa/matchjob?color=56BEB8" /> -->
</p>

<!-- Status -->

<h4 align="center"> 
	🚧  Matchjob 🚀 En cours de développement ..  🚧
</h4> 

<hr>

<p align="center">
  <a href="#dart-a-propos">A propos</a> &#xa0; | &#xa0; 
  <a href="#sparkles-features">Features</a> &#xa0; | &#xa0;
  <a href="#sparkles-a-faire">Features</a> &#xa0; | &#xa0;
  <a href="#rocket-technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#white_check_mark-dépendances">Dépendances</a> &#xa0; | &#xa0;
  <a href="#checkered_flag-installation">Installation</a> &#xa0; | &#xa0;
  <a href="https://github.com/Noumaa" target="_blank">Nouma V.</a> &#xa0; | &#xa0;
  <a href="https://github.com/AFelix20100" target="_blank">Félix A.</a>
</p>

<br>

## :dart: A propos ##

Trouvez l'emploi de vos rêves

## :sparkles: Features ##

:heavy_check_mark: S'inscrire;\
:heavy_check_mark: Se connecter;\
:heavy_check_mark: Fixer la page d'accueil pendant des heures !;

## :sparkles: A faire ##

- [ ] A faire
- [x] Fait

## :rocket: Technologies ##

Les outils suivants ont été beaucoup sollicités :

- [Symfony](https://symfony.com/)
- [Bootstrap](https://getbootstrap.com/)

## :white_check_mark: Dépendances ##

L'utilisation de [PHP](https://www.php.net/) est requise, version 8.1 minimum.  
Les extensions `cURL`, `intl` et `openSSL` sont également requises.

## :checkered_flag: Installation ##

```bash
# Cloner le dépôt
$ git clone https://github.com/Noumaa/matchjob

# Se rendre au bon endroit (c'est mieux)
$ cd matchjob

# Installer les dépendences
$ composer install

# Faire les migrations (pour la base de données)
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate

# Charger les jeux de données
$ php bin/console doctrine:fixtures:load

# Lancer le serveur !
$ symfony server:start # Requiert Symfony CLI

# Le serveur se sera initialisé sur <http://localhost:8000>
```

### Remarques

- Lancer un serveur en utilisant Symfony CLI ne sera pas suffisant lors d'un déploiement. Pour plus d'informations rendez-vous sur la documentation du serveur de votre choix (Apache, nginx, etc).
- Par défaut, MatchJob génère une base de données SQLite à des fins de développement. Lors d'un déploiement il est préférable d'installer un "vrai" SGBDR tel que MySQL, PostgreSQL, etc. [Plus d'informations](https://symfony.com/doc/current/doctrine.html#configuring-the-database).

<!-- ## :memo: Licence ##

This project is under license from MIT. For more details, see the [LICENSE](LICENSE.md) file.


Fait avec :heart: par <a href="https://github.com/Noumaa" target="_blank">Nouma</a> -->

&#xa0;

<a href="#top">Retour en haut !</a>
