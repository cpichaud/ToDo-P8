# ToDo-P8

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3d3cde78f5874a51aa7e734e872d2e34)](https://app.codacy.com/gh/cpichaud/ToDo-P8/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Configuration 

- MySQL / MariaDB
- PHP 8.2
- Symfony 5.4

## Installation du Projet

### Cloner le Projet

Pour obtenir une copie locale du projet, utilisez la commande suivante :

```
git clone https://github.com/cpichaud/ToDo-P8.git
```
### Installation des Dépendances

Dans votre terminal, exécutez la commande suivante pour installer les dépendances du projet à l'aide de Composer :

```
composer install
```
### Configuration de l'Environnement
Assurez-vous que votre environnement est correctement configuré, notamment la base de données. Vous devrez créer un fichier env.local pour votre configuration locale. Voici un exemple de contenu pour ce fichier :


```
DATABASE_URL=mysql://nom_utilisateur:mot_de_passe@localhost:3306/nom_de_la_base_de_donnees
APP_DEBUG=true
APP_SECRET=cle_secrete_unique_pour_votre_application
APP_URL=http://localhost:8000
```
Assurez-vous de personnaliser les valeurs avec vos informations spécifiques.

### Migrations
Pour créer les tables de la base de données, exécutez la migration à l'aide de la commande suivante :

```
php bin/console doctrine:migrations:migrate
```

### Chargement des Fixtures
Pour charger les données de test dans la base de données, exécutez la commande suivante :

```
php bin/console doctrine:fixtures:load
```
Cela ajoutera des données de test pour vous aider à démarrer.

Une fois que vous avez suivi ces étapes, votre projet Symfony devrait être configuré et prêt à être utilisé. 

