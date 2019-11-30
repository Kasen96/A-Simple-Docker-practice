use mysql;
create user 'docker'@'%' identified by '123456';
grant all on docker_todolist.* to 'docker'@'%';
flush privileges;