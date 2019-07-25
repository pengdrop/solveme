[![Rawsec's CyberSecurity Inventory](https://inventory.rawsec.ml/img/badges/Rawsec-inventoried-FF5050_flat.svg)](https://inventory.rawsec.ml/ctf_platforms.html#Solve%20Me)
[![GitHub stars](https://img.shields.io/github/stars/safflower/solve-me.svg)](https://github.com/safflower/solve-me/stargazers)
[![GitHub license](https://img.shields.io/github/license/safflower/solve-me.svg)](https://github.com/safflower/solve-me/blob/master/LICENSE)

# Solve Me

![main](https://i.imgur.com/JaxGpfV.png)

It's jeopardy style wargame website called Solve Me.

This source code tested on `PHP 7.2` / `MariaDB 10.1.40`.

~Official Website: <http://solveme.peng.kr/>~

Demo Website: <http://211.239.124.233:20813/>


## How to setup?

```
# in main server
docker run -it -p 20813:80 --name solveme ubuntu /bin/bash
```

```
# in docker container
apt update
apt install -y vim lrzsz unzip

# apache
apt install -y apache2
apache2 -t
a2enmod rewrite
vim /etc/apache2/sites-available/000-default.conf
>> <Directory /var/www/>
>>     Options Indexes FollowSymLinks MultiViews
>>     AllowOverride All
>>     Order allow,deny
>>     allow from all
>> </Directory>
service apache2 start

# php
apt install -y php7.2 php-mbstring pdo-mysql
php -v
vim /etc/php/7.2/apache2/php.ini
# plz enable `mbstring` and `pdo-mysql`

# mysql
apt install -y mariadb-server
service mysql start
mysql_secure_installation
mysql -u root -p
>> create database `solveme`;
>> use solveme;
>> grant all privileges on *.* to root@localhost identified by 'your_password';
>> flush privileges;
>> exit;

# download source code
cd /var/www/html
rz
unzip solveme.zip
```
