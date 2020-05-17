FROM nginx:1.17

MAINTAINER Sergey Shevchenko <sergey.leonidovich.shevchenko@gmail.com>

# Ставим все необходимые для работы пакеты
RUN apt-get update     \
    && apt-get install -y \
           curl           \
           less           \
           vim

# Копируем nginx-конфигурацию внутрь контейнера
COPY ./build/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./build/nginx/conf.d/symfony-starter.conf /etc/nginx/conf.d/symfony-starter.conf

RUN rm /etc/nginx/conf.d/default.conf

# Устанавливаем рабочую дирректорию
WORKDIR /etc/nginx

# Entrypoint
COPY ./build/nginx/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80 443

CMD ["nginx-debug", "-g", "daemon off;"]
