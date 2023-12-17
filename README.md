
# Weather App - Symfony

An app that show the current weather for a given city, and log in history the weather data every refresh of the page.


## Requirements

- PHP 7.2.5 or higher
- Postgresql database
- PDO-pgSQL PHP extension enabled
- Symfony CLI


## Installation

Execute the commands bellow to install the project:

```bash
  git clone git@github.com:lackynasta/weather-app.git
  cd weather-app
  composer install
```

## Database and migration

Make a copy of the .env file and name it .env.local
Change the database parameters in .env.local file according to your database

`DATABASE_URL="postgresql://user:password@127.0.0.1:5432/weather"`

Execute the commands below :
```bash
  php bin/console doctrine:database:create
  php bin/console make:migration
  php bin/console doctrine:migrations:migrate
```


## Fixtures
Run the command below to add fixtures to your database
```bash
  php bin/console doctrine:fixtures:load
```

## Run the App

Execute this command to run the built-in web server and access the application in your browser at https://localhost:8000

```bash
  symfony server:start
```


