# A Simple Docker Practice Project.

The aim is to be familiar with the docker and docker commands. In this project, I use some basic docker cmds, also learn how to write Dockerfile and connect containers by using docker network, and use docker compose and docker swam by setting docker-compose.yml.

## Todo List Application

I build a simple todolist application presented by web pages. To check the source code, you can see the `code` directory.

### Environments

I use PHP:7.3, Nginx:latest and Mysql:5.7.

### Functions

* login
* logout
* register
* show todo list
* add todo list
* delete todo list

### Database

In the database, I use two tables, one is the user table to store user infomation, the other is the list table to store the todolist infomation.

## Docker Containers

### Dockerfile: PHP

```dockerfile
FROM php:7.3-fpm-alpine

RUN docker-php-ext-install mysqli
```

#### Layers

```shell
docker history docker-php_php-workspace
IMAGE               CREATED             CREATED BY                                      SIZE                COMMENT
1f4aea0971a6        22 minutes ago      /bin/sh -c docker-php-ext-install mysqli        367kB
3fc70c6602c0        9 days ago          /bin/sh -c #(nop)  CMD ["php-fpm"]              0B
<missing>           9 days ago          /bin/sh -c #(nop)  EXPOSE 9000                  0B
<missing>           9 days ago          /bin/sh -c #(nop)  STOPSIGNAL SIGQUIT           0B
<missing>           9 days ago          /bin/sh -c set -eux;  cd /usr/local/etc;  if…   25.4kB
<missing>           9 days ago          /bin/sh -c #(nop) WORKDIR /var/www/html         0B
<missing>           9 days ago          /bin/sh -c #(nop)  ENTRYPOINT ["docker-php-e…   0B
<missing>           9 days ago          /bin/sh -c docker-php-ext-enable sodium         168kB
<missing>           9 days ago          /bin/sh -c #(nop) COPY multi:c1ff99c7805e8f4…   6.5kB
<missing>           9 days ago          /bin/sh -c set -eux;  apk add --no-cache --v…   56.1MB
<missing>           9 days ago          /bin/sh -c #(nop) COPY file:ce57c04b70896f77…   587B
<missing>           9 days ago          /bin/sh -c set -eux;   apk add --no-cache --…   12.1MB
<missing>           9 days ago          /bin/sh -c #(nop)  ENV PHP_SHA256=aafe5e9861…   0B
<missing>           9 days ago          /bin/sh -c #(nop)  ENV PHP_URL=https://www.p…   0B
<missing>           9 days ago          /bin/sh -c #(nop)  ENV PHP_VERSION=7.3.12       0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV GPG_KEYS=CBAF69F173A0…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHP_LDFLAGS=-Wl,-O1 -…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHP_CPPFLAGS=-fstack-…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHP_CFLAGS=-fstack-pr…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHP_EXTRA_CONFIGURE_A…   0B
<missing>           5 weeks ago         /bin/sh -c set -eux;  mkdir -p "$PHP_INI_DIR…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHP_INI_DIR=/usr/loca…   0B
<missing>           5 weeks ago         /bin/sh -c set -eux;  addgroup -g 82 -S www-…   4.86kB
<missing>           5 weeks ago         /bin/sh -c apk add --no-cache   ca-certifica…   2.74MB
<missing>           5 weeks ago         /bin/sh -c #(nop)  ENV PHPIZE_DEPS=autoconf …   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  CMD ["/bin/sh"]              0B
<missing>           5 weeks ago         /bin/sh -c #(nop) ADD file:fe1f09249227e2da2…   5.55MB
```

### Dockerfile: Nginx

```dockerfile
FROM nginx:alpine

COPY conf.d /etc/nginx/conf.d
```

#### Layers

```shell
docker history docker-php_nginx
IMAGE               CREATED             CREATED BY                                      SIZE                COMMENT
7f8a57649b57        23 minutes ago      /bin/sh -c #(nop) COPY dir:b6be4f9ecc46a5558…   625B
a624d888d69f        11 days ago         /bin/sh -c #(nop)  CMD ["nginx" "-g" "daemon…   0B
<missing>           11 days ago         /bin/sh -c #(nop)  STOPSIGNAL SIGTERM           0B
<missing>           11 days ago         /bin/sh -c #(nop)  EXPOSE 80                    0B
<missing>           11 days ago         /bin/sh -c set -x     && addgroup -g 101 -S …   15.9MB
<missing>           11 days ago         /bin/sh -c #(nop)  ENV PKG_RELEASE=1            0B
<missing>           11 days ago         /bin/sh -c #(nop)  ENV NJS_VERSION=0.3.7        0B
<missing>           11 days ago         /bin/sh -c #(nop)  ENV NGINX_VERSION=1.17.6     0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  LABEL maintainer=NGINX Do…   0B
<missing>           5 weeks ago         /bin/sh -c #(nop)  CMD ["/bin/sh"]              0B
<missing>           5 weeks ago         /bin/sh -c #(nop) ADD file:fe1f09249227e2da2…   5.55MB
```

## Docker Compose

```yaml
version: '3'

services:
  php-workspace:
    container_name: lab1-php
    build:
      context: ./php
      dockerfile: Dockerfile
    restart: always
    depends_on:
      - mysql-db
    volumes:
      - ./code:/var/www/html/
    networks: 
      - front
      - back

  nginx:
    container_name: lab1-nginx
    build:
      context: ./nginx
      dockerfile: Dockerfile
    restart: always
    depends_on:
      - php-workspace
      - mysql-db
    ports:
      - "8080:8080"
    volumes:
      - ./log/nginx:/var/log/nginx/
    networks: 
      - front

  mysql-db:
    container_name: lab1-mysql
    image: mysql:5.7
    restart: always
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: "123"
    volumes:
      - ./mysql/db_data:/var/lib/mysql
      - ./mysql/init:/docker-entrypoint-initdb.d/
    networks: 
      - back

networks: 
  front:
    driver: "bridge"
  back:
    driver: "bridge"

```



## Docker Swarm



