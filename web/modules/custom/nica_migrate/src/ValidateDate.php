<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\ValidateDate.
 */

namespace Drupal\nica_migrate;


class ValidateDate {

    public static function positionChangeDate($date_csv)
    {
      if (!empty($date_csv)) {
        $data_value = explode('/', $date_csv);
          return date("Y-m-d", mktime(0, 0, 0, $data_value[0], $data_value[1], $data_value[2]));
        }else {
            return "";
        }
    }

    public static function dateEmployment() {

    }
}
