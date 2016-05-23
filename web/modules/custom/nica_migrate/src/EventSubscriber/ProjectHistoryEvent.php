<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\ProjectHistoryEvent.
 */


namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectHistoryEvent implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[MigrateEvents::PREPARE_ROW][] = array('onPrepareRow', 0);
    return $events;
  }

  /**
   * React to a new row.
   *
   * @param \Drupal\migrate_plus\Event\MigratePrepareRowEvent $event
   *   The prepare-row event.
   */
  public function onPrepareRow(MigratePrepareRowEvent $event) {
    if ($event->getMigration()->id() == 'project_history') {
      $row = $event->getRow();
      $expoded = explode('-', $row->getSourceProperty('year_trim'));
      if (count($expoded) == 2 && is_numeric($expoded[0] && is_numeric($expoded[1]))) {
        list($year, $trim) = explode('-', $row->getSourceProperty('year_trim'));
        $row->setSourceProperty('year', $year);
        $row->setSourceProperty('trim', $trim);
      }
    }
  }
}
