<?php

/**
 * @file
 * Contains \Drupal\migrate\Plugin\migrate\process\PHMaterials.
 */

namespace Drupal\nica_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin allows source value convert to number format.
 *
 * @MigrateProcessPlugin(
 *   id = "ph_materials"
 * )
 */
class PHMaterials extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    for ($i = 0; $i < 4; $i++) {
      if (empty($value[1][$i]) || $value[1][$i] == 'NA') {
        continue;
      }
      $value[] = [$value[0], $value[1][$i], $value[2], $value[3], $value[4]];
    }
    for ($i = 0; $i < 5; $i++) {
      unset($value[$i]);
    }
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }
}
