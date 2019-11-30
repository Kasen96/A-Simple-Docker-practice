#!/bin/bash

set -e

echo '1. start MySQL...'
service mysql start
echo `service mysql status`

echo '2. start to run script.sql'
mysql < /mysql/script.sql
echo 'done'
echo `service mysql status`

echo '3. start to change password and add user'
mysql < /mysql/privileges.sql
echo 'done'
echo `service mysql status`

tail -f /dev/null
