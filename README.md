Kalaxia Portal
===============

This repository is a Symfony project for the Kalaxia open-source community portal website.

Anyone following the contributing rules are welcome to contribute to the project.

Setup
--------

This project uses Docker and Docker Compose to setup the environment. 

This environment consists in the following technologies:

* PHP 7.x
* MySQL
* PHPMyAdmin
* Nginx
* MongoDB
* Citadel Technologies Feedback Manager

You can clone the project with the following command

```
git clone git@github.com:Kalaxia/portal.git kalaxia-portal
cd kalaxia-portal
cp portal.dist.env portal.env
cp .env.dist .env
```

In the two configuration files that you copied, the default values can be kept to run the application in a dev environment.

Once it's done, you can launch your Docker containers

```
docker-compose up -d
```

### Application setup

Now that the containers are running, you must setup the Symfony application for the portal.

Go inside the portal container:

```
docker-compose exec app bash
```

Here, you can install the project dependencies and setup the files permissions.

The Doctrine command line is a setup of the project database tables schema. It will create all the needed tables.

The security command line generates the RSA keypair, used to establish a communication with the game servers.

```
composer install
chown -R www-data:www-data var/cache
chown -R www-data:www-data var/logs
chown -R www-data:www-data var/sessions
./bin/console doctrine:schema:update --force
./bin/console security:rsa:generate
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

### Admin tasks

If you want to clear the cache, get into the portal container and then type the following commands:

```
./bin/console cache:clear
chown -R www-data:www-data var/cache
```

If you want to give roles to a user, use FOS User built-in command:

```
php bin/console fos:user:promote
```

To make an admin account, you must promote your user account to ROLE_SUPER_ADMIN. This will allow the account to access admin dashboard and servers administration.

### Game server creation

Once you're logged in with an admin account, you can access the administration dashboard.

First, you must create one or many factions. In the "banner" field, set one of the following :

* purple_blades.png
* golden_companions.png
* azure.png

Set the colors you want. The game uses for now only the main color, used for fonts, borders and backgrounds styles in the game, so set a readable color for this value.

Then, you must create a machine, representing a host that will run your servers.

In the servers dashboard, create a local machine with the name you want, and copy the public game server RSA KEY content (``public.pub``) in the associated field. If the key is valid the interface will show a blue fingerprint of it. The value to use if you are using the docker-compose in local is `kalaxia_nginx`


When the machine is created, you can create a server. Select the machine you just created, select the factions, and then choose a name and a begin date anterior to the current date. Leave the subdomain alone. You can type a short description.

If the server creation worked, you shall be redirected to the admin dashboard. You can see the created server, and join it from the member dashboard "Mon espace".

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
