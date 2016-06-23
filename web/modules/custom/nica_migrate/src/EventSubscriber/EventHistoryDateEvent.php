<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\EmploymentDateEvent.
 */

namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\nica_migrate\ValidateDate;
use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmploymentDateEvent implements EventSubscriberInterface {

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        $events[MigrateEvents::PREPARE_ROW][] = array('onPrepareRow', 0);
        return $events;

    }

    public function onPrepareRow(MigratePrepareRowEvent $event) {

        if ($event->getMigration()->id() == 'employment_history') {

            $row = $event->getRow();
            $date_empl = $row->getSourceProperty('employment_date');

            //$date_nica = explode("-", $date_empl);
            $employment_date = ValidateDate::validate($date_empl);

            $row->setSourceProperty('employment_date', $employment_date); 
        }
    }
}
