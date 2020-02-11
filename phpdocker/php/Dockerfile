FROM phpdockerio/php72-fpm:latest
WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php-xdebug \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && echo "zend_extension=xdebug.so" > /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.max_nesting_level=1500" >> /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /etc/php/7.2/mods-available/xdebug.ini \
    && echo "xdebug.remote_host=docker.for.mac.localhost" >> /etc/php/7.2/mods-available/xdebug.ini
