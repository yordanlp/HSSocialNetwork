
# Harbour Space Social Network

Project for Module 3 (Modern Web Applications 1) of Master in Computer Science at [Harbour.Space](https://harbour.space/).
It's a small social network similar to [twitter](https://twitter.com/), built using Laravel framework.


## Features

- Users can post text with or without a picture
- Users can follow other users and see their posts
- Users can comment, like or dislike other users posts


## Dependencies

- [Laravel 9.x](https://laravel.com/docs/9.x/installation)
- PHP 8
- [Nginx or Apache Web Server](https://laragon.org/download/index.html)
- [MySql](https://laragon.org/download/index.html)
- [Composer](https://getcomposer.org/download/)
## Run Locally on Windows

Clone the project

```bash
  git clone https://github.com/yordanlp/HSSocialNetwork.git
```

Copy to folder C:\laragon\www

Run laragon services

Go to the project directory

```bash
  cd HSSocialNetwork
```

Rename .env.example to .env

Install dependencies

```bash
  composer install
```

Generate key: 

```bash
  php artisan key:generate
```

Run Migrations

```bash
  php artisan migrate:fresh --seed
```

Start the server

```bash
  php artisan serve
```


## Authors

- [@yordanlp](https://www.github.com/yordanlp)

