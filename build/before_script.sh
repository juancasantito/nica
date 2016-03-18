#!/usr/bin/env bash
# Add repo and install apache & fastcgi.
sudo apt-add-repository multiverse && sudo apt-get update
sudo apt-get install apache2 libapache2-mod-fastcgi -yq --no-install-suggests --no-install-recommends
# Enable php-fpm.
sudo a2enmod rewrite actions fastcgi alias
cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.conf
# Work around travis issue #3385
if [ "$TRAVIS_PHP_VERSION" = "7.0" -a -n "$(ls -A ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d)" ]; then
  sudo cp ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf.default ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.d/www.conf
fi
echo "cgi.fix_pathinfo = 1" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
~/.phpenv/versions/$(phpenv version-name)/sbin/php-fpm
# Disable sendmail for FPM for when serving the actual site.
echo sendmail_path=`which true` >> ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.ini
# Enable APC
echo "extension=apc.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.ini
echo "apc.shm_size=256M" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php-fpm.ini
# Configure apache virtual hosts
sudo cp -f build/travis-ci-apache /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default.conf
echo "127.0.0.1 travis" | sudo tee -a /etc/hosts
sudo service apache2 restart
