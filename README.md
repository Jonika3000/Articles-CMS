# Laravel Filament CMS

This project involves developing a simple Content Management System (CMS) using Laravel and Filament. The CMS will allow users to manage articles, leveraging Laravel's MVC structure, Eloquent ORM, authentication, middleware, and RESTful APIs.

Article Management: Create, read, update, and delete (CRUD) articles. Also pagination and search.

User Authentication: Secure the CMS with user authentication.

RESTful APIs: Endpoints to interact with articles programmatically.

Admin Interface: Use Filament for a user-friendly admin panel.

## Endpoints

* GET api/article - List all articles
* GET api/article/show/{id} - Get a specific article
* POST api/article - Create a new article (auth only)
* PUT /article/{id} - Update an existing article (auth as article author only)
* DELETE /article/{id} - Delete an article (auth as article author only)

## Requirements

* PHP >= 8.1
* Composer
* PostgreSQL or any supported database

## Technology Stack

* Laravel v.10.44.0
* Filament v.3.2
* Laravel Breeze
* PHPUnit
* PostgreSQL

## Getting Started

```
git clone https://github.com/Jonika3000/Articles-CMS.git
```
```
cd Articles-CMS
```
```
composer install
```
```
cp .env.example .env
```
```
in env set yours database and mailer settings
```
```
php artisan key:generate
```
```
npm i
```
```
npm run build
```
```
php artisan migrate
```
```
php artisan db:seed
```
```
php artisan serve
```
```
Login or register!
```

![image](https://github.com/user-attachments/assets/40ac8738-9742-4b77-86bd-25ca46c162b3)
![image](https://github.com/user-attachments/assets/56e63dc4-9239-457e-a1cb-a17b62d0edb8)

