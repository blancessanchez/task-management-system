
## Task Management Demo

This project is a simple and user-friendly platform that allows project managers to manage their tasks efficiently.

## Tech Stack

- PHP 7.4.33
- Laravel 8
- Bootstrap 5.3
- Vue.js 2.7.14
  - Vue Draggable 2.24.3

## Run Locally

- Clone the project

```bash
  git clone git@github.com:blancessanchez/task-management-system.git
```

- Create database named **task_management**.

- Go to the project directory

```bash
  cd task-management-system
```

- Execute the necessary setup

```bash
  php artisan migrate
  php artisan key:generate
  npm install
```

- Go to the .env file, change the following according to the credentials

```bash
  DB_HOST=127.0.0.1
  DB_DATABASE=task_management
  DB_USERNAME=root
  DB_PASSWORD=
```

- Start the server and build assets

```bash
  php artisan serve
  npm run prod
```

- Execute test script

```bash
  php artisan test --filter Feature
```
