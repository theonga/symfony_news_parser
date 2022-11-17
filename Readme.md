# News Parser using PHP 7.4 Symfony 5.4, MySQL, RabbitMQ, Nginx Docker

## This is a complete stack for running Symfony 5.4 into Docker containers using docker-compose tool with docker-sync library.

It is composed by 4 containers:

- php - the PHP-FPM container with the 7.4 version of PHP.
- database - which is the MySQL database container with a MySQL 8.0 image.
- RabbitMQ for running parallel tasks
- symfony_docker_app_sync to sync files using library docker-sync .

## How to run

1. Clone the repository
2. Create the file ./.docker/.env.nginx.local using ./.docker/.env.nginx as template. The value of the variable NGINX_BACKEND_DOMAIN is the server_name used in NGINX.
3. Go inside folder ./docker and run docker-sync-stack start to start containers.
4. You should work inside the php container. 
5. Inside the php container, run composer install to install dependencies from /var/www/symfony folder.


## How to fetch the news

There is a command for fetching news from ([Pindula API](https://zero.pindula.co.zw/api/posts/) : symfony console app:news:fetch