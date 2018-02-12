<<<<<<< HEAD

# thanatosPos
Thanatos POS + Integration with Woocommerce

- Mysql must be 5.7 ++
- Change memory_limit on php.ini to more than 512M ( 1024M recommended )
- PHP 7.0 Recommended
- Dont forget to install Redis, MongoDB, Mongod Driver for PHP 7
- Dont forget to run the queue listener "php artisan queue:work --daemon --sleep=3 --tries=3"

# HOW TO INSTALL ( go inside root dir first, make sure nodeJs 8++ installed, and NPM 5++ installed, composer installed )
- composer install
- cd public
- bower install
- cd ..
- php artisan migrate

# Script for create geolocation ( run this after migrating )
- mkdir storage/geo
- cd storage/geo
- wget http://download.geonames.org/export/dump/allCountries.zip && unzip allCountries.zip && rm allCountries.zip
- wget http://download.geonames.org/export/dump/hierarchy.zip && unzip hierarchy.zip && rm hierarchy.zip
- then run 'php artisan thanatos:geoseed'

# Dont forget to run the socket.js server (realtime server)
- go to root dir
- cd realtime_server
- node socket.js


# PHP Module Requirements
- bcmath
- bz2
- calendar
- com_dotnet
- Core
- ctype
- curl
- date
- dom
- exif
- fileinfo
- filter
- gd
- gettext
- gmp
- hash
- iconv
- imap
- ionCube Loader
- json
- libxml
- mbstring
- mcrypt
- mongodb
- mysqli
- mysqlnd
- openssl
- pcre
- PDO
- pdo_mysql
- pdo_sqlite
- Phar
- readline
- Reflection
- session
- SimpleXML
- soap
- sockets
- SPL
- sqlite3
- standard
- tidy
- tokenizer
- wddx
- xdebug
- xml
- xmlreader
- xmlrpc
- xmlwriter
- xsl
- zip
- zlib
=======
# thanatosPolos
Polosan
>>>>>>> 03714335288a39520c6e8631e3c9850347cbebe4
