#!/bin/bash

clear
rm -rf ./var/cache/*

RANDOM_STRING=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

echo 'Создание и наполнение переменных окружений в проект...'

MYSQL_CREDENTIAL=
if [[ ! -z ${MYSQL_USER} ]]; then
    MYSQL_CREDENTIAL=${MYSQL_USER}
    if [[ ! -z ${MYSQL_PASSWORD} ]]; then
        MYSQL_CREDENTIAL="${MYSQL_CREDENTIAL}:${MYSQL_PASSWORD}"
    fi
    if [[ ! -z ${MYSQL_CREDENTIAL} ]]; then
        MYSQL_CREDENTIAL="${MYSQL_CREDENTIAL}@"
    fi
fi
echo "MYSQL_CREDENTIAL: ${MYSQL_CREDENTIAL}"

cp ./.env.dist './.env' && chmod 777 './.env'
sed -i "s/^APP_ENV=/APP_ENV=${APP_ENV}/" './.env'
sed -i "s/^APP_SECRET=/APP_SECRET=${RANDOM_STRING}/" './.env'
sed -i "s,^DATABASE_URL=,DATABASE_URL=mysql://${MYSQL_CREDENTIAL}employer-cashbox-mysql:3306/${MYSQL_DATABASE}," './.env'
sed -i "s/^LOCALE=/LOCALE=ru_Ru/" './.env'
sed -i "s/^MYSQL_HOST=/MYSQL_HOST=employer-cashbox-mysql/" './.env'
sed -i "s/^MYSQL_PORT=/MYSQL_PORT=3306/" './.env'
sed -i "s/^MYSQL_ROOT_PASSWORD=/MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}/" './.env'
sed -i "s/^MYSQL_DATABASE=/MYSQL_DATABASE=${MYSQL_DATABASE}/" './.env'
sed -i "s/^MYSQL_USER=/MYSQL_USER=${MYSQL_USER}/" './.env'
sed -i "s/^MYSQL_PASSWORD=/MYSQL_PASSWORD=${MYSQL_PASSWORD}/" './.env'
sed -i "s/^NGINX_HOST_HTTP_PORT=/NGINX_HOST_HTTP_PORT=${NGINX_HOST_HTTP_PORT}/" './.env'
sed -i "s/^WORKSPACE_IP=/WORKSPACE_IP=employer-cashbox-mysql/" './.env'
source ./.env

echo "In xdebug.ini OLD ==> $(grep remote_host /usr/local/etc/php/conf.d/xdebug.ini)"
sed -i "s/\${XDEBUG_REMOTE_HOST}/${XDEBUG_REMOTE_HOST}/g" /usr/local/etc/php/conf.d/xdebug.ini
echo "In xdebug.ini NEW ==> $(grep remote_host /usr/local/etc/php/conf.d/xdebug.ini)"

echo 'Подтягиваем composer зависимости...'
composer install -n

echo 'Запускаем миграции...';
php bin/console doctrine:schema:update --force --no-interaction

echo 'Запускаем php-fpm...'
exec "${@}"
