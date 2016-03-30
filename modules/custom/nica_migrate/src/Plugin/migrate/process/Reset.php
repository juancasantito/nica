<?php

/**
 * @file
 * Contains \Drupal\migrate\Plugin\migrate\process\Reset.
 */

namespace Drupal\nica_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin allows source value convert to number format.
 *
 * @MigrateProcessPlugin(
 *   id = "reset"
 * )
 */
class Reset extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if ($row->getDestinationProperty('field_project_leader') || $row->getDestinationProperty('field_project_group')) {
      return is_array($value) ? reset($value) : $value;
    }
    return NULL;
  }

}
