# Snowtricks
Project for OpenClasssrooms : create an API Rest

<div align="center">
    <br>
    <img src="https://upload.wikimedia.org/wikipedia/fr/0/0d/Logo_OpenClassrooms.png" width="120" height="120" alt="logo OpenClassrooms">
</div>


## What I used for this project :


### Main languages :

<img src="https://img.shields.io/badge/php-8.3.1-%23777BB4?logo=php" alt="php banner"> <img src="https://img.shields.io/badge/symfony-6.4-%25%23000000%3F?logo=symfony" alt="Static Badge">


### Database :

<img src="https://img.shields.io/badge/MySQL-8.0.30-%234479A1?logo=mysql" alt="MySQL banner"> <img src="https://img.shields.io/badge/HeidiSQL-12.1.0-%234479A1?logo=mysql" alt="MySQL banner"> <img src="https://img.shields.io/badge/Laragon-6.0-%230E83CD?logo=laragon" alt="MySQL banner">


### Tools :

<img src="https://img.shields.io/badge/Composer-2.6.5-%23885630?logo=composer" alt="composer banner"> <img src="https://img.shields.io/badge/fakerPHP-1.23.1-%23000000"  alt="fakerPHP banner"> <img src="https://img.shields.io/badge/Postman-10.22-%23FF6C37?logo=Postman" alt="Postman banner"> <img src="https://img.shields.io/badge/swagger-4.8-%2385EA2D?logo=swagger" alt="Swagger banner"> <img src="https://img.shields.io/badge/LexikJWTAuthenticationBundle-2.20-%2300CAFF" alt="LexikJWTAuthenticationBundle banner"> <img src="https://img.shields.io/badge/Hateoas-3.0-%23E6E6E6" alt="Hateoas banner"> 



## How to run this project :

To run this project, you need to use composer, and run :

```
composer install
```

For your secret informations (database, SMTP and so on..), copy the ```.env``` file in a ```.env.local``` file and replace all the data that begins by *your_* by yours

To create the database run :

```
php bin/console doctrine:database:create
```

Then to create the tables run :
```
php bin/console doctrine:migrations:migrate
```
answer : y

To load the fake fixtures run :

```
php bin/console doctrine:fixtures:load
```
answer : y


### To try : ###
* You can use "adminBilemo" or pick an email in the database and use it. The password is *mdpass* for all fake users.  
