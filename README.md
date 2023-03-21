# Fruity Test

## Requirements 
 - php: >= 8.1
 - Symfony CLI
 - db: PostgreSQL 
 - yarn or npm

## Recomendation
 - use yarn instead of npm

## Instalation (DEV environment)

Clone the repo go to the project directory
```sh
git clone https://github.com/0AT13/fruity-test-task
cd fruity-test-task/
```

Install composer dependencies by running
```sh
symfony composer install
```

### Database and SMTP Configuration
In case you need to set up your own database connection or smpt server
copy **.env** file to **.env.local**

Specify these variables with your settings in **.env.local** file:
```sh
## For Database
POSTGRES_HOST=127.0.0.1
POSTGRES_DB=db
POSTGRES_USER=root
POSTGRES_PASSWORD=password
POSTGRES_PORT=5432

## For Emails
# An email that will receive emails about the addition of new fruits
ADMIN_EMAIL=exmaple@gmail.com
# Your SMTP server
MAILER_DSN=smtp://user:pass@smtp.example.com:port
```

### Default Setup
Run docker for PostgreSQL Database and Mailcatcher, **in this case emails will not be sent to real email adress** (If you use both of your own database and SMTP server you can skip this step):
```sh
docker-compose up -d
```

Run migrations:
```sh
symfony console doctrine:migrations:migrate
```

Run yarn or npm:
```sh
yarn install
yarn dev

npm install
npm run dev
```

Run Symfony server and run worker for emails:
```sh
symfony serve -d
symfony console messenger:consume async -vv
```

Now you can run the command to fill database with Fruits!

Use this command to fetch them all:
```sh
symfony console app:fetch-fruit
```
Or to get only one of them, for exmaple:
```sh
symfony console app:fetch-fruit apple
```
