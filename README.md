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

* `docker build -t <image>:<version> .` - *Don’t foget to add the `.` !!!*

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

* `docker-compose up`
* `docker-compose down`

### Version

```yaml
version: '3'
```

### PHP

```yaml
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
```

### Nginx

```yaml
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
```

### MySQL

```yaml
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
```

### Networks

```yaml
networks: 
  front:
    driver: "bridge"
  back:
    driver: "bridge"
```

## Docker Swarm Mode

### Docker Swarm Commands

* `docker swarm init` : become manager node.
* `docker swarm leave`
* `docker swarm leave --force` : for manager node.

### Docker Stack Commands

* `docker stack deploy -c docker-compose.yml <name>`
* `docker stack ls` : list stacks
* `docker stack ps <name>` : list tasks in the stack
* `docker stack services <name>` : list services in the stack
* `docker stack down <name>` 

### Docker Service Commands

* `docker service ls`

  ```yaml
  docker service ls
  ID                  NAME                      MODE                REPLICAS            IMAGE                             PORTS
  x8l52xbdlhkd        stack-php_mysql-db        replicated          1/1                 mysql:5.7                         *:3306->3306/tcp
  lfu2zebydvtj        stack-php_nginx           replicated          3/3                 docker-php_nginx:latest           *:8080->8080/tcp
  r5abbfc4lx8n        stack-php_php-workspace   replicated          3/3                 docker-php_php-workspace:latest
  3suredg8dskd        stack-php_visualizer      replicated          1/1                 dockersamples/visualizer:stable   *:9090->8080/tcp
  ```

* `docker service ps <name>`

  ```yaml
  docker service ps stack-php_nginx
  ID                  NAME                IMAGE                     NODE                DESIRED STATE       CURRENT STATE            ERROR               PORTS
  cmrmgwv0y7ym        stack-php_nginx.1   docker-php_nginx:latest   docker-desktop      Running             Running 16 minutes ago
  sndpvwh3utqe        stack-php_nginx.2   docker-php_nginx:latest   docker-desktop      Running             Running 16 minutes ago
  zbhhvyza9j4r        stack-php_nginx.3   docker-php_nginx:latest   docker-desktop      Running             Running 16 minutes ago
  ```

* `docker service logs <name>`

* `docker service scale <name>=<number>` : number > number_now, scale up; number < number_now, scale down.

  ```yaml
  docker service scale stack-php_nginx=5
  stack-php_nginx scaled to 5
  overall progress: 5 out of 5 tasks
  1/5: running   [==================================================>]
  2/5: running   [==================================================>]
  3/5: running   [==================================================>]
  4/5: running   [==================================================>]
  5/5: running   [==================================================>]
  verify: Service converged
  ```

  ```yaml
  docker service scale stack-php_nginx=3
  stack-php_nginx scaled to 3
  overall progress: 3 out of 3 tasks
  1/3: running   [==================================================>]
  2/3: running   [==================================================>]
  3/3: running   [==================================================>]
  verify: Service converged
  ```

* `docker service rm <name>`

### Notice for docker-compose.yml

* `build:` can’t be used, `image:` must exists.
  * Download `php-workspace`  image: `docker pull nullptrz/customized-php-of-lab1:v1` .
  * Download `nginx` image: `docker pull nullptrz/customized-nginx-of-lab1:v1` .
* `restart` can’t be used, use `restart_policy` instead.
* change `bridge` network to `overlay` in swarm mode.

### PHP

```yaml
  php-workspace:
    image: docker-php_php-workspace:latest
    deploy:
      mode: replicated
      replicas: 3
    depends_on:
      - mysql-db
    volumes:
      - ./code:/var/www/html/
    networks: 
      - front
      - back
```

### Nginx

```yaml
  nginx:
    image: docker-php_nginx:latest
    deploy:
      mode: replicated
      replicas: 3
    depends_on:
      - php-workspace
      - mysql-db
    ports:
      - "8080:8080"
    volumes:
      - ./log/nginx:/var/log/nginx/
    networks: 
      - front
```

### MySQL

```yaml
  mysql-db:
    image: mysql:5.7
    deploy:
      placement:
        constraints: 
          - node.role == manager
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: "123"
    volumes:
      - ./mysql/db_data:/var/lib/mysql
      - ./mysql/init:/docker-entrypoint-initdb.d/
    networks: 
      - back
```

### Visualized Page

* Visit `http:localhost:9090 ` for visualized page

```yaml
  visualizer:
    image: dockersamples/visualizer:stable
    ports:
      - "9090:8080"
    stop_grace_period: 1m30s
    volumes:
      - "/var/run/docker.sock:/var/run/docker.sock"
    deploy:
      placement:
        constraints: 
          - node.role == manager
```

### Networks

```yaml
networks: 
  front:
    driver: "overlay"
  back:
    driver: "overlay"
```



# LICENSE

               GLWT(Good Luck With That) Public License
                 Copyright (c) Everyone, except Author

Everyone is permitted to copy, distribute, modify, merge, sell, publish,
sublicense or whatever they want with this software but at their OWN RISK.

                            Preamble

The author has absolutely no clue what the code in this project does.
It might just work or not, there is no third option.


                GOOD LUCK WITH THAT PUBLIC LICENSE
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION, AND MODIFICATION

  0. You just DO WHATEVER YOU WANT TO as long as you NEVER LEAVE A
TRACE TO TRACK THE AUTHOR of the original product to blame for or hold
responsible.

IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.

Good luck and Godspeed.

