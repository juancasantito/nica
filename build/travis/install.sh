#!/usr/bin/env bash
set -ev

composer --verbose install
cd ${TRAVIS_BUILD_DIR}/web
printf "<?php\n\$databases['default']['default'] = ['database' => 'travis', 'username' => 'root', 'password' => '', 'prefix' => '', 'host' => 'localhost', 'port' => '3306', 'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql', 'driver' => 'mysql'];" > ${TRAVIS_BUILD_DIR}/web/sites/default/settings.local.php
${TRAVIS_BUILD_DIR}/vendor/bin/drush site-install config_installer -y --verbose --db-url=mysql://root:@127.0.0.1/travis --db-su='root' --db-su-pw=''
${TRAVIS_BUILD_DIR}/vendor/bin/drush runserver --dns localhost:8080 - &
sleep 3
