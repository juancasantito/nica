<?php

/**
 * @file
 * Contains \Drupal\migrate\Plugin\migrate\process\NumberFormat.
 */

namespace Drupal\nica_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin allows source value convert to number format.
 *
 * @MigrateProcessPlugin(
 *   id = "number_format"
 * )
 */
class NumberFormat extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return ($new_value = strstr($value, ',', TRUE)) ? $new_value : $value;
  }

}
