<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\ValidateDate.
 */

namespace Drupal\nica_migrate;


class ValidateDate {

  public static function validate($date_nica) {
    $return = [];
    if (!empty($date_nica)) {
      $date_exploded = explode("-", $date_nica);
      foreach ($date_exploded as $date) {
        $date = explode('/', $date);
        if (count($date) == 2) {
          array_splice($date, 1, 0, [1]);
        }
        if (count($date) == 1) {
          array_splice($date, 0, 0, [1, 1]);
        }
        list($month, $day, $year) = $date;
        $return[] = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
      }
    }
    return $return;
  }

}
