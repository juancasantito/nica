<?php

// DB credentials.
$databases['default']['default'] = [
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\Core\Database\Driver\mysql',
  'driver' => 'mysql'
];

/**
 * Assertions.
 *
 * The Drupal project primarily uses runtime assertions to enforce the
 * expectations of the API by failing when incorrect calls are made by code
 * under development.
 *
 * @see http://php.net/assert
 * @see https://www.drupal.org/node/2492225
 *
 * If you are using PHP 7.0 it is strongly recommended that you set
 * zend.assertions=1 in the PHP.ini file (It cannot be changed from .htaccess
 * or runtime) on development machines and to 0 in production.
 *
 * @see https://wiki.php.net/rfc/expectations
 */
assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

/**
 * Show all error messages, with backtrace information.
 *
 * In case the error level could not be fetched from the database, as for
 * example the database connection failed, we rely only on this value.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

/**
 * Enable local development services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/default.services.yml';
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.redis.yml';

/**
 * Skip file system permissions hardening.
 *
 * The system module will periodically check the permissions of your site's
 * site directory to ensure that it is not writable by the website user. For
 * sites that are managed with a version control system, this can cause problems
 * when files in that directory such as settings.php are updated, because the
 * user pulling in the changes won't have permissions to modify files in the
 * directory.
 */
$settings['skip_permissions_hardening'] = TRUE;

/**
 * Disable the render cache (this includes the page cache).
 *
 * Note: you should test with the render cache enabled, to ensure the correct
 * cacheability metadata is present. However, in the early stages of
 * development, you may want to disable it.
 *
 * This setting disables the render cache by using the Null cache back-end
 * defined by the development.services.yml file above.
 *
 * Do not use this setting until after the site is installed.
 */
$settings['cache']['bins']['render'] = 'cache.backend.null';

/**
 * Disable Dynamic Page Cache.
 *
 * Note: you should test with Dynamic Page Cache enabled, to ensure the correct
 * cacheability metadata is present (and hence the expected behavior). However,
 * in the early stages of development, you may want to disable it.
 */
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

/**
 * Override solr server config.
 */
$config['search_api.server.default_solr_server']['backend_config']['host'] = 'localhost';
$config['search_api.server.default_solr_server']['backend_config']['port'] = 8983;
$config['search_api.server.default_solr_server']['backend_config']['username'] = NULL;
$config['search_api.server.default_solr_server']['backend_config']['password'] = NULL;

/**
 * Redis
 */
// Allow the services to work before the Redis module itself is enabled.
$settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';
// Manually add the classloader path, this is required for the container cache bin definition below
// and allows to use it without the redis module being enabled.
$class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');
// Set Redis as the default backend for any cache bin not otherwise specified.
$settings['cache']['default'] = 'cache.backend.redis';
$settings['redis.connection']['host'] = '127.0.0.1';
// Set a fixed prefix so that all requests share the same prefix, even if
// on different domains.
$settings['cache_prefix'] = 'nepc_';
// Use redis for container cache.
// The container cache is used to load the container definition itself, and
// thus any configuration stored in the container itself is not available
// yet. These lines force the container cache to use Redis rather than the
// default SQL cache.
$settings['bootstrap_container_definition'] = [
  'parameters' => [],
  'services' => [
    'redis.factory' => [
      'class' => 'Drupal\redis\ClientFactory',
    ],
    'cache.backend.redis' => [
      'class' => 'Drupal\redis\Cache\CacheBackendFactory',
      'arguments' => ['@redis.factory', '@cache_tags_provider.container'],
    ],
    'cache.container' => [
      'class' => '\Drupal\redis\Cache\PhpRedis',
      'factory' => ['@cache.backend.redis', 'get'],
      'arguments' => ['container'],
    ],
    'cache_tags_provider.container' => [
      'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
      'arguments' => ['@redis.factory'],
    ],
  ],
];
// Drupal 8.1 has a bug where certain special caches that should use the
// APCu cache if available will not do so if a non-SQL default is specified.
// The following lines explicitly force those cache bins to use the correct
// cache backend. This block may be removed in Drupal 8.2.
// @see https://www.drupal.org/node/2753989
$settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
$settings['cache']['bins']['static'] = 'cache.backend.memory';
