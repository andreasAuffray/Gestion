# Tasker
Tasker is a simple project-management app.

## Setting up
To setup the project, you can just copy the `.env.default` to `.env`, and change the content as required, particularly for the user and password.

## How to build
All you need to run is docker compose, in build mode. You can use the following:
```sh
docker compose build
```

## How to run
All you need to run is docker compose:
```sh
docker compose
```

## Run the migrations
To run the migration, you first need to start the docker and enter it. You can enter it by using the following:
```sh
make bash
```
And then, in the container, use:
```sh
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Deploying
Before performing the build, copy `compose.prod.yaml` to `compose.override.yaml`. This will make frankenphp run in production mode instead of dev.
Do not forget to change the APP_SECRET variable. You can generate a new one with PHP:
```sh
php -r "echo bin2hex(random_bytes(32));"
```
