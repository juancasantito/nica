<?php

/**
 * @file
 * Contains \Drupal\migrate\Plugin\migrate\process\SpecialTrim.
 */

namespace Drupal\nica_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin allows source value convert to number format.
 *
 * @MigrateProcessPlugin(
 *   id = "special_trim"
 * )
 */
class SpecialTrim extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return preg_replace('/\s/u', '', $value);
  }

}
