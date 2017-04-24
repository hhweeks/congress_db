#!/usr/bin/env bash

# Modified from https://github.com/panique/vagrant-lamp-bootstrap/blob/master/bootstrap.sh

# Modified from https://gist.github.com/clavery/7cec1a3107a976ada41b

DOCUMENT_ROOT='/vagrant/congress_web' # change if using a subdirectory in your project
MYSQL_ROOT_PASSWORD='vagrant'
DROP_DB_IF_EXISTS=0 # Set to 1 to drop databases that exist
DATABASES=congress

### Bookeeping ###
mkdir -p /root/.provisioning

### Apache + PHP ###
apt-get update -y
apt-get upgrade -y
apt-get install -y apache2
apt-get install -y php5 libapache2-mod-php5 php5-cli php5-gd


# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# install apache 2.5 and php 5.5
sudo apt-get install -y apache2
sudo apt-get install -y php5

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_ROOT_PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD"
sudo apt-get -y install mysql-server php5-mysql

cat <<CONFIG > /etc/apache2/sites-enabled/000-default.conf
<VirtualHost *:80>
	ServerAdmin webmaster@localhost

	DocumentRoot ${DOCUMENT_ROOT}
	<Directory />
		Options FollowSymLinks
		AllowOverride All
		Require all granted
	</Directory>
	<Directory ${DOCUMENT_ROOT}>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride all
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
CONFIG

sudo a2enmod rewrite

service apache2 restart

service mysql enable

service mysql restart

cd /vagrant
if [ -d "import_data" ]; then
	echo "Importing mysql congress data"
	mysql -u root --password=${MYSQL_ROOT_PASSWORD} <<-MSQL
		create database if not exists congress;
		use congress;
		source createTable.sql;
		source insertData.sql;
	MSQL
fi
