Kalaxia Portal
===============

This repository is a Symfony project for the Kalaxia open-source community portal website.

Anyone following the contributing rules are welcome to contribute to the project.

Setup
--------

This project uses Docker to setup the environment.

This environment consists in the following technologies:

* PHP 7.x
* MySQL
* PHPMyAdmin
* Nginx
* MongoDB
* Citadel Technologies Feedback Manager

So you need Docker on your machine to make it work.

If you prefer to have the whole environment without Docker, you can skip the Docker setup tutorial

### Docker

First, install both Docker and Docker Compose

* [Docker for Windows](https://docs.docker.com/toolbox/toolbox_install_windows/)
* [Docker for Mac and Linux](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Once it's done, install the local copy of the project on your computer.

```
git clone git@github.com:Kalaxia/portal.git kalaxia-portal
```

Then go to the created folder, and copy the containers configuration file.

You do not need to edit the default values of this file.

You can finally launch the Docker Compose setup:

```
cd kalaxia-portal
cp portal.dist.env portal.env
docker-compose up -d
```

At the end of the install, you can check that the containers are running using ```docker-compose ps```.

The output shall be similar to :

```
         Name                        Command               State                    Ports                  
-----------------------------------------------------------------------------------------------------------
portal_feedback_manager   go-wrapper run                   Up      80/tcp                                  
portal_mongodb            docker-entrypoint.sh mongod      Up      27017/tcp                               
portal_mysql              docker-entrypoint.sh mysqld      Up      3306/tcp                                
portal_nginx              /entrypoint.sh nginx -g da ...   Up      0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp
portal_phpfpm             docker-php-entrypoint php-fpm    Up      9000/tcp                                
portal_phpmyadmin         /home/entrypoint.sh              Up      0.0.0.0:8082->80/tcp  
```

Now, you have to log in the application container.

```
docker exec -it portal_phpfpm /bin/bash
```

### Application setup

Here, you can install the project dependencies and setup the files permissions.

When setting up the dependencies with composer install, **you will be prompted some config parameter values**.

Please leave the default values, they are related to the containers configuration.

The Doctrine command line is a setup of the project database tables schema. It will create all the needed tables.

The security command line generates the RSA keypair, used to establish a communication with the game servers.

```
composer install
chown -R www-data:www-data var/cache
chown -R www-data:www-data var/logs
chown -R www-data:www-data var/sessions
php bin/console doctrine:migrations:migrate
php bin/console security:rsa:generate
```

You have now to edit the /etc/hosts file to setup the application web address.

In Windows, this file is located in C:\Windows\System32\drivers\etc\hosts.

In both cases, the file must be edited in administrator mode.

You have to add the following line:

```
127.0.0.1 local.portal.com
```

In your web browser, you can go to http://local.portal.com.

Usage
-----

### Start and stop

To stop the containers, use ```docker-compose stop```

To set them up again, use ```docker-compose up -d```

### Admin tasks

If you want to clear the cache, get into the container and then type the following commands:

```
php bin/console cache:clear
chown -R www-data:www-data var/cache
```

If you want to give roles to a user, use FOS User built-in command:

```
php bin/console fos:user:promote
```

### Code update

**Do not forget to keep your local copy updated !** To update your local branch with the last works of the team, run the following commands:

```
git fetch
git rebase origin/develop
```

```git fetch``` will retrieve the remote updates. If the output is empty, it means that nothing has been updated.

If you see the ```develop``` branch has been updated, use ```rebase``` to retrieve the last commits and set your own in the Git history.

This step is mandatory when you will want to merge your work with develop. Your branch must be updated.

### Database update

When updating, you can - and you should - check if there are new database migrations to execute.

To do so, you can run **in the portal container** (``docker exec -it portal_phpfpm /bin/bash``): 

```
php bin/console doctrine:migrations:status
```

It will tell you if there are new migrations scripts.

To execute them until you are fully updated, type:

```
php bin/console doctrine:migrations:migrate
```

If you need to add tables or modify it, use Doctrine documentation to generate a new migration file (you can ask to Kern who will guide you safely on this adventurous path ;)).

### System update

Sometimes, dependencies are updated. Then you have to run this into a console :

```
composer install
```
