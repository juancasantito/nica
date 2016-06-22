<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\ValidateDateEvent.
 */

namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\nica_migrate\ValidateDate;
use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ValidateDateEvent implements EventSubscriberInterface {

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        $events[MigrateEvents::PREPARE_ROW][] = array('onPrepareRow', 0);
        return $events;

    }

    public function onPrepareRow(MigratePrepareRowEvent $event) {

        if ($event->getMigration()->id() == 'profile') {

            $row = $event->getRow();
            $date_csv = $row->getSourceProperty('birthday');
            $date_edit = ValidateDate::positionChangeDate($date_csv);
            $row->setSourceProperty('birthday', $date_edit);
        }
    }
}
