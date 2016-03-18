<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\Plugin\migrate\source\Materials.
 */

namespace Drupal\nica_migrate\Plugin\migrate\source;

use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;

/**
 * @inheritdoc
 *
 * @MigrateSource(
 *   id = "materials"
 * )
 */
class Materials extends CSV {

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    $file = parent::initializeIterator();
    return $this->getYield($file);
  }

  /**
   * @param $file
   * @return \Generator
   */
  public function getYield($file) {
    foreach ($file as $line_num => $line) {
      for ($i = 1; $i <= 4; $i++) {
        $new_line = [];
        $new_line['id'] = $line["ID"];
        $new_line['profile'] = $line["Profile ID"];
        $new_line['year'] = $line["Year"];
        $new_line['trim'] = $line["Trim."];
        $new_line['material'] = $line["Material$i"];
        $new_line['cantidad'] = $line["Cantidad$i"];
        $new_line['valor'] = $line["Valor$i"];
        $new_line['measurement'] = isset($line["Unidad$i"]) ? $line["Unidad$i"] : '';
        yield $new_line;
      }
    }
  }

}
