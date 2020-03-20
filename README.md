## Budget My Funds Application

[![Build Status](https://travis-ci.org/anasey01/BudgetMyFunds.svg?branch=master)](https://travis-ci.org/anasey01/BudgetMyFunds)
[![Maintainability](https://api.codeclimate.com/v1/badges/e47bb498fd283c8d8b47/maintainability)](https://codeclimate.com/github/anasey01/BudgetMyFunds/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e47bb498fd283c8d8b47/test_coverage)](https://codeclimate.com/github/anasey01/BudgetMyFunds/test_coverage)


An API implementation for Users willing to manage their finances

The application API is hosted on Heroku and available on [here](http://core-api.budgetmyfunds.com)

### Table of Contents

  - Technologies
  - Setup and Installation

### Technologies
The technologies used are:

* [PHP](http://www.php.net/) - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Laravel](https://laravel.com/docs/6.x) - For web applications
* [Twillo](https://www.twilio.com/docs/usage/api) - Twillo sends programable SMS.

### Installation

#### Required Softwares

BudgetMyFunds App requires some softwares and Installations to run

- Git (https://git-scm.com/docs)
- Docker (https://www.docker.com/)
- SequelPro (https://sequelpro.com/download)

The links above provide download and installation steps

Install the dependencies and devDependencies and start the server.

#### Codebase Setup

1. Navigate to your preferred directory to clone the project

```
$ git clone https://github.com/anasey01/BudgetMyFunds.git

```

2. Navigate to the newly created project directory (BudgetMyFunds)

```
$ cd BudgetMyFunds

```

3. Create your .env and copy .env.example to .env, then Fill in your database variables

```
$ cp .env.example .env

```

4. Install all the required dependencies as well as dev depencencies

```
$ composer install

```

5. Confirm that you have docker installed

```
$ docker --version

```

6. Pull and Build docker containers

```
$ docker-compose up -d 

```

7. Confirm that all continers are up and running

```
$ docker-compose ps

```

Your app should be running on [localhost](0.0.0.0:8081)

####   Connecting to your Database on Docker via Seqlel Pro

1. Get the ip and port number associated with your mysql service

```
$ docker-compose ps

```
You should see an output similar to
```
          Name                         Command              State           Ports
------------------------------------------------------------------------------------------
mysql     docker-entrypoint.sh mysqld      Up      0.0.0.0:13306->3306/tcp
nginx     nginx -g daemon off;             Up      0.0.0.0:8080->80/tcp
php       docker-php-entrypoint php-fpm    Up      0.0.0.0:9000->9000/tcp
redis     docker-entrypoint.sh redis ...   Up      0.0.0.0:6379->6379/tcp
sqlite3   sqlite3                          Up      0.0.0.0:43306->4306/tcp
```

2. Open seqel-pro and add this connection details
```
Host 0.0.0.0
Port 3306 (as seen on the port for the postgress container)
User The value for DB_USERNAME defined in your .env
Password The value for DB_PASSWORD defined in your .env
Database  The value for DB_DATABASE defined in your .env
```

3. Your connection should be successful

4. Test your app by running the migration

```
docker exec -it php php artisan migrate
```

OR IF you decide to run all commands directly in the conainer, you can follow these step to excute commands.

```
$ docker exec -it php bash
```

Now in the linux container instance,

```
$ php artian migrate
```

Your migrations should run successfully

####   Testing Locally

- Add a `database.testing.sqlite` in your `database` folder and run 

```
$ docker exec -it php composer test
```

OR in bash Docker container, run 

```
$ composer test
```

HAPPY CODING!!!

Common docker commands
 - Use `docker-compose start` command to restart all containers.
 - Use `docker-compose stop` command to stop all containers.
 - Use `docker start <container id>` to start a specific container.
 - Use `docker stop <container id>` to stop a specific container.
 - Use `docker images` command to view all docker images.
 - Use `docker ps or docker ps -a` to view all containers. The -a flag means stopped containers are listed as well.
 - Use `docker system prune -a to` remove all stopped containers and images at once.
 - Use `docker rmi <image id> `or `docker rmi <image name>:<tag>` to remove a specific image.
 - Use `docker rm <container id>` to remove a specific stopped container.
 - Use `docker-compose exec <service-name> <command>` command to execute commands in a container, where service-name is the docker service (for an image/container) specified in the docker-compose.yml file. For example to execute the artisan command to clear laravel config cache for the php-fpm service we run docker-compose exec php-fpm php artisan config:clear. Other artisan commands are run similarly against the docker container containing our laravel app.

## Contributing

Thank you for the outstanding and collaborative work. You can find individual contributions in closed/open pull requests

- [Fredrick Adegoke](https://github.com/Frediflexta)
- [Mark Adeniran](https://github.com/Maxfurry)

