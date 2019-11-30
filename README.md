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

### Dockerfile: Nginx

```dockerfile
FROM nginx:alpine

COPY conf.d /etc/nginx/conf.d
```

### Dockerfile: MySQL

```dockerfile
FROM mysql:5.7

ENV MYSQL_ALLOW_EMPTY_PASSWORD=yes

COPY setup.sh /mysql/setup.sh
COPY script.sql /mysql/script.sql
COPY privileges.sql /mysql/privileges.sql

CMD ["sh", "/mysql/setup.sh"]
```



## Docker Compose

```yaml
version: '3'

services:
  php-workspace:
    container_name: lab1-php
    build: ./php
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
    build: ./nginx
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
    #image: mysql:5.7
    build: ./mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: "123"
      MYSQL_USER: "docker"
      MYSQL_PASSWORD: "123456"
    volumes:
      - ./mysql/db_data:/var/lib/mysql
    networks: 
      - back

networks: 
  front:
    driver: "bridge"
  back:
    driver: "bridge"

```



## Docker Swarm



