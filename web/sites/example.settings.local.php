<?php

/**
 * @file
 * Local development override configuration feature.
 */

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

/**
 * Debug
 */
$config['system.logging']['error_level'] = 'verbose';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['discovery_migration'] = 'cache.backend.memory';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['extension_discovery_scan_tests'] = FALSE;
$settings['rebuild_access'] = TRUE;
$settings['skip_permissions_hardening'] = TRUE;

/**
 * Database info.
 */
$databases['default']['default'] = array (
  'database' => 'drupal8',
  'username' => 'drupal8',
  'password' => 'drupal8',
  'prefix' => 'pyr_',
  'host' => 'database',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

/**
 * Installation info.
 */
$settings['install_profile'] = 'config_installer';

/**
 * Configuration management
 */
$config_directories['sync'] = '../config/sync';
$config['config_split.config_split.local']['status'] = true;
$config['config_split.config_split.dev']['status'] = true;
$config['config_split.config_split.stage']['status'] = false;
$config['config_split.config_split.prod']['status'] = false;

/**
 * Custom config split to export agile module config files. 
 */
$config['config_split.config_split.agile']['status'] = false;
