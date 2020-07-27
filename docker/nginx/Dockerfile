FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

RUN apt-get update && apt-get -y install cron

COPY sitemap /etc/cron.d/sitemap

RUN chmod 0644 /etc/cron.d/sitemap

RUN crontab /etc/cron.d/sitemap

RUN touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log

ADD nginx.conf /etc/nginx/

RUN usermod -u 1000 www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443