# Prérequis

+ php 7.2
+ composer
+ mysql

# Installation

### 1. cloner le repos

```bash
$ git clone https://github.com/opmvpc/biblio.git
$ cp biblio
```

### 2. Installation avec composer

```bash
$ composer install
```

**Guide officiel ici: https://laravel.com/docs/6.x#configuration**

### 3. Copier le fichier de config

```bash
$ cp .env.example .env
```

### 4. Puis renseigner les infos de votre DB

### 5. Générer la clé d'encryption

```bash
$ php artisan key:generate
```

### 6. Population de la DB

```bash
$ php artisan migrate:fresh --seed
```

### 7. Permissions
pas besoin avec laragon
```bash
$ sudo chmod -R g+w bootstrap/cache/
$ sudo chmod -R g+w storage/
```

### 8. upload fichiers

```bash
$ php artisan storage:link
```

### 9. assets js css dans une deuxieme fenetre de commande (pas besoin si on ne modifie pas le code js ou css)

```bash
$ npm install && npm run watch
```

Et normalement ça devrait fonctionner
