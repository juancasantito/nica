<?php

/**
 * @file
 * Contains \Drupal\nica_entity\Controller\NicaEntityController.
 */

namespace Drupal\nica_entity\Controller;

use Drupal\entity\Controller\EntityCreateController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @inheritdocs
 */
class NicaEntityController extends EntityCreateController {
  /**
   * @inheritdoc
   */
  public function addPage($entity_type_id, Request $request) {
    $build['#theme'] = 'nica_entity_add_list';
    return $build + parent::addPage($entity_type_id, $request);
  }
}
