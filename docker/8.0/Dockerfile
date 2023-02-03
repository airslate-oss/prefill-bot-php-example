FROM debian:11.6-slim

LABEL Description="Development image for airSlate Prefill from JSON Bot"

WORKDIR /app

RUN apt update && apt upgrade -y && \
    apt install -y ca-certificates lsb-release apt-transport-https software-properties-common gnupg2 wget curl && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list && \
    wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add - && \
    apt update && \
    apt install -y php8.0 \
        php8.0-bcmath \
        php8.0-dom \
        php8.0-curl \
        php8.0-mbstring \
        php8.0-xdebug \
        unzip \
        supervisor && \
    # install composer
    php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    # remove artifacts
    apt-get -y autoremove && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY php.ini /etc/php/8.0/cli/conf.d/99-app.ini
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8000

CMD ["/usr/bin/supervisord", "--configuration=/etc/supervisor/conf.d/supervisord.conf", "-n"]
