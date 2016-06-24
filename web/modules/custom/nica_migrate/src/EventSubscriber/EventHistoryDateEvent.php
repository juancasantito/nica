<?php
/**
 * @file
 * Contains \Drupal\nica_migrate\EventSubscriber\EventHistoryDateEvent.
 */

namespace Drupal\nica_migrate\EventSubscriber;

use Drupal\nica_migrate\ValidateDate;
use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventHistoryDateEvent implements EventSubscriberInterface {

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        $events[MigrateEvents::PREPARE_ROW][] = array('onPrepareRow', 0);
        return $events;

    }

    public function onPrepareRow(MigratePrepareRowEvent $event) {

        if ($event->getMigration()->id() == 'event_history') {
            $row = $event->getRow();
            $date_hist = $row->getSourceProperty('event_date');
            $history_date = ValidateDate::validate($date_hist);
            $row->setSourceProperty('event_date', $history_date);

            $result = $row->getSourceProperty('result');
            $result_data = $this->searchTaxonomy($result);
            $row->setSourceProperty('result', $result_data);
            $row->setSourceProperty('comments', $result);
        }
    }

    protected function searchTaxonomy($data_search) {
        $found = '';
        $comp = ['Bueno', 'Malo', 'Excelente'];
        if (!empty($data_search)) {
            foreach ($comp as $c) {
                if (preg_match("/" . $c . "/i", $data_search, $found)) {
                    $found = ucwords(reset($found));
                    break;
                }
            }
        }
        return $found;
    }
}
