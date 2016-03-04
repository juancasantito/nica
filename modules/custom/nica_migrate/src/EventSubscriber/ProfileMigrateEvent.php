<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\ProfileMigrateEvent.
 */


namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProfileMigrateEvent implements EventSubscriberInterface {

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
    $row = $event->getRow();

//    $row->setSourceProperty('first_last', $row->getSourceProperty('first_name') . ' ' . $row->getSourceProperty('last_name'));
  }
}
