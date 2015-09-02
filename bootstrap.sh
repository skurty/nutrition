#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='test'
PROJECTFOLDER='nutrition'
DATABASE='nutrition'

# create project folder
if [ ! -d "/var/www/${PROJECTFOLDER}" ]; then
	sudo mkdir "/var/www/${PROJECTFOLDER}"
fi

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# install apache 2.5 and php 5.5
sudo apt-get install -y apache2
sudo apt-get install -y php5

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

# install phpmyadmin and give password(s) to installer
# for simplicity I'm using the same password for mysql and phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# import database
mysql -u root -p${PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DATABASE};"
mysql -u root -p${PASSWORD} ${DATABASE} < /var/www/${PROJECTFOLDER}/ws/db/db.sql

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/${PROJECTFOLDER}"
    
    <Directory "/var/www/${PROJECTFOLDER}">
        AllowOverride All
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

sudo a2enmod rewrite

service apache2 restart

sudo apt-get -y install git vim curl

curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# configure ws
sudo chmod 777 "/var/www/${PROJECTFOLDER}/ws/logs"

DATABASECONFIG=$(cat <<EOF
<?php

$app['debug'] = true;

$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../../logs/dev.log',
	'monolog.name'    => 'nutrition'
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
		'driver'   => 'pdo_mysql',
		'dbname'   => 'nutrition',
		'host'     => 'localhost',
		'user'     => 'root',
		'password' => '',
		'charset'  => 'utf8'
	)
));
EOF
)
echo "${DATABASECONFIG}" > /var/www/${PROJECTFOLDER}/ws/app/config/prod.php