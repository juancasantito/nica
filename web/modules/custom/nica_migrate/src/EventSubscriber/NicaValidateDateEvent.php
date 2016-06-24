<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\NicaValidateDateEvent.
 */

namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\nica_migrate\ValidateDate;
use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NicaValidateDateEvent implements EventSubscriberInterface {

  /**
   * @return array The event names to listen to
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::PREPARE_ROW][] = ['onPrepareRow', 0];
    return $events;
  }

  public function onPrepareRow(MigratePrepareRowEvent $event) {
    $migration = $event->getMigration()->id();
    $migrations = [
      'profile' => 'birthday',
      'employment_history' => 'employment_date',
      'event_history' => 'event_date',
      'project_history' => 'project_date'
    ];

    foreach ($migrations as $migration_id => $source_field) {
      if ($migration_id == $migration) {
        $row = $event->getRow();
        $value = $row->getSourceProperty($source_field);
        $date = ValidateDate::validate($value);
        $row->setSourceProperty($source_field, $date);
        break;
      }
    }
  }

}
