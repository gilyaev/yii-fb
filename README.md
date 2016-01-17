# yii-fb
The service for getting public wall posts with the facebook API.

## Installation and configurations
* Create virtual host directory for our service. E.g. the host directory path is /var/www/fb-api ([HOST_DIR]) 
* Clone git repository into host directory
```
# git clone https://github.com/gilyaev/yii-fb.git [HOST_DIR]
```
* Change the *DocumentRoot* directive so that it referred to [HOST_DIR]/api/www
```
<VirtualHost *:80>
    ServerAdmin admin@example.com
    DocumentRoot [HOST_DIR]/api/www
    ServerName api.example.com
    <Directory [HOST_DIR]/api/www>
      Options Indexes FollowSymLinks MultiViews
      AllowOverride All
      Order allow,deny
      allow from all
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
* Install composer dependencies
```
# cd ./HOST_DIR/
# composer install
```
* Create local config files:
```
# mv [HOST_DIR]/app/config/main.local-example.php [HOST_DIR]/app/config/main.local.php
# mv [HOST_DIR]/common/config/main.local-example.php [HOST_DIR]/common/config/main.local.php     
``` 
* Specify the Facebook app settings (app id, app secret) in [HOST_DIR]/app/config/main.local.php
* Specify the mongo DB settings in [HOST_DIR]/common/config/main.local.php
* Run DB migrations
```
[HOST_DIR]# ./yiic migratemongo --migrationPath=common.migrations
```

