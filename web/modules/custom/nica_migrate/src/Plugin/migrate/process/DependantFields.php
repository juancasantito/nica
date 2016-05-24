<?php

/**
 * @file
 * Contains \Drupal\migrate\Plugin\migrate\process\Reset.
 */

namespace Drupal\nica_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin allows source value convert return a single value if at least
 * one dependant field is populated.
 *
 * @MigrateProcessPlugin(
 *   id = "dependant_fields"
 * )
 */
class DependantFields extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (isset($this->configuration['field_dependencies'])) {
      $field_dependencies = $this->configuration['field_dependencies'];
       if (!is_array($field_dependencies)) {
        $field_dependencies = [$field_dependencies];
      }
      $return = FALSE;
      foreach ($field_dependencies as $field) {
        if ($row->getDestinationProperty($field)) {
          $return = TRUE;
          break;
        }
      }

      if ($return) {
        return is_array($value) ? reset($value) : $value;
      }
      else {
        return NULL;
      }
    }
    else {
      throw new MigrateException('field_dependecies is require configuration');
    }
  }

}
