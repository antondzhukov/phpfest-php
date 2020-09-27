Demo project with gRPC and protobuf especially for PHPFest conference

## Installation

### Environment

see https://grpc.io/docs/languages/php/quickstart/

### Project

```$bash

git clone https://github.com/antondzhukov/phpfest-php.git

composer install

```

Protofiles will be generated automatically. See generateproto.sh for details

## Nginx and php-fpm configs

Project contains configuration files for nginx and php-fpm. Just change template domain.

![#f03c15] Warning: this server configuration without custom tunes. Change parameters by your server features 

#### nginx config/nginx/phpfest.loc.conf

```$nginx
server {
    server_name phpfest.loc;
    root /www/phpfest.loc/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php-fpm/phpfest.loc.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/phpfest_error.log;
    access_log /var/log/nginx/phpfest_access.log;
}

```

#### php-fpm config/nginx/phpfest.loc.conf

```$php-fpm
[phpfest_loc]

listen = /var/run/php-fpm/phpfest.loc.sock
listen.allowed_clients = 127.0.0.1
listen.owner = some_user
listen.group = nginx
listen.mode = 0660
user = some_user
group = some_user
pm = static
pm.max_children = 15
pm.max_requests = 500
pm.status_path = /phpfest-loc-status
slowlog = /var/log/php-fpm/phpfest.loc.slow.log
rlimit_core = unlimited
php_admin_value[error_log] = /var/log/php-fpm/phpfest.loc.error.log
php_admin_flag[log_errors] = on
php_value[session.name] = 'PHPFEST_SESSID'
php_value[session.gc_probability] = 0a
php_value[session.gc_maxlifetime] = 2592000
php_value[upload_max_filesize] = 25M
php_value[post_max_size] = 50M
php_value[max_input_vars] = 100000
php_admin_value[memory_limit] = 2048M
```

Next open http://your_domain.loc/getGRPC for show results