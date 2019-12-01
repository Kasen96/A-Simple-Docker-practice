-- 创建数据库

create database if not exists docker_todolist charset utf8;

use docker_todolist;

-- 建表

drop table if exists list;

create table list
(
    list_id mediumint unsigned auto_increment
        primary key,
    content text         null,
    user_id mediumint(8) null
)
    charset = utf8;

drop table if exists user;

create table user
(
    user_id  mediumint unsigned auto_increment
        primary key,
    name     varchar(30) null,
    password char(40)    null,
    email    varchar(60) null,
    constraint email
        unique (email),
    constraint name
        unique (name)
)
    charset = utf8;


