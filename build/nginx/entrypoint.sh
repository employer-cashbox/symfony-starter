#!/bin/bash

set -eux

function addLogMessage {
    echo -e "\n>>>>>>>>>>>> $1 <<<<<<<<<<<<\n"
}


## Заменяем переменные на значения в конфигурациях (названия и их значения берем из env)
#addLogMessage 'Заменяем переменные на значения в конфигурациях (названия и их значения берем из env)...'
#addLogMessage "/etc/nginx/cors-disabled.conf OLD ==> $(grep Access-Control-Allow-Origin /etc/nginx/cors-disabled.conf)"
#IFS=$'\n'
#for ENV_VARIABLE in $(env | grep RBK)
#do
#    ENV_VARIABLE_NAME=$(echo ${ENV_VARIABLE} | cut -f1 -d=)
#    ENV_VARIABLE_VALUE=$(echo ${ENV_VARIABLE} | cut -f2 -d=)
#    sed -i "s,\${${ENV_VARIABLE_NAME}},${ENV_VARIABLE_VALUE},g" /etc/nginx/cors-disabled.conf
#done
#addLogMessage "/etc/nginx/cors-disabled.conf NEW ==> $(grep Access-Control-Allow-Origin /etc/nginx/cors-disabled.conf)"


addLogMessage 'Запускаем nginx...'
exec "${@}"
