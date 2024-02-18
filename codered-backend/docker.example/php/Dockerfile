FROM intcore/php-8.0-fpm

# Copy composer.lock and composer.json
# COPY code/composer.lock* code/composer.json* /var/www/

# Set working directory
WORKDIR /var/www

#    ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
#
#RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
#    install-php-extensions mbstring pdo \

# Install dependencies
RUN apt-get update --fix-missing
#RUN apt-get upgrade
RUN apt-get install -y \
     apt-transport-https \
     build-essential \
     libpng-dev \
     libjpeg62-turbo-dev \
     libfreetype6-dev \
     locales \
     libzip-dev \
     libpng-dev \
     libwebp-dev \
     zip \
     jpegoptim optipng pngquant gifsicle \
     unzip \
     curl \
     nano \
     cron \
     supervisor \
     rsyslog \
     sudo \
     dos2unix \
     procps \
     ffmpeg \
     libwebp-dev \
     wget



# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
#RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
#RUN docker-php-ext-install mbstring

RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-webp-dir=/usr/include/ --with-jpeg --with-png-dir=/usr/include/ --with-webp=/usr/include/
RUN docker-php-ext-install gd
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Fix user permission for containers
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
COPY --chown=www:www . /var/www


# Copy existing application directory contents
COPY ./code /var/www


# configrations
COPY ./docker/php/supervisord/supervisord.conf /etc/supervisor
COPY ./docker/php/supervisord/scripts/* /etc/supervisor/conf.d/
COPY ./docker/php/crons/* /etc/cron.d/
COPY ./docker/php/rsyslog/rsyslog.conf /etc


# create log files
RUN touch /var/log/cron.log
RUN touch /var/www/storage/logs/crontab.log
RUN touch /var/www/storage/logs/supervisord.log

# fix encruption of schedule
RUN dos2unix /etc/cron.d/schedule
# add schedule to crontab
RUN crontab /etc/cron.d/schedule

RUN service rsyslog restart
RUN service cron restart

RUN cd /opt/ && wget 'https://staticdownloads.site24x7.com/apminsight/agents/agent_php.zip'
RUN cd /opt/ && unzip agent_php.zip
RUN cd /opt/zpa/bin && sh configure.sh us_0d56917dcef74382b16ac7b587f62b97 Codered-Stage
RUN cd /opt/zpa/bin && sh run.sh start


RUN chmod -R 777 ./new-project.sh
RUN chmod -R 777 ./storage

# Expose port 9000 and start supervisord server
# Supervisord will start all main service [php, cronjobs, laravel commands ...etc]
EXPOSE 9000
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
