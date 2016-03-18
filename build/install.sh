#!/usr/bin/env bash
set -ev
mysql -e 'create database IF NOT EXISTS travis;' -uroot
export PATH="$HOME/.composer/vendor/bin:$PATH"
composer global require drush/drush:dev-master --dev --prefer-dist
phpenv rehash
drush site-install config_installer -y --verbose --db-url=mysql://root:@127.0.0.1/travis --db-su='root' --db-su-pw=''
