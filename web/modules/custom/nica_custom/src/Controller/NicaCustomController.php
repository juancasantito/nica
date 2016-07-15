<?php

/**
 * @file
 * Contains \Drupal\nica_custom\Controller\NicaCustomController.
 */

namespace Drupal\nica_custom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityInterface;

class NicaCustomController extends ControllerBase {

  public function curriculumvitae(ContentEntityInterface $nica_entity) {
    return [
      '#theme' => 'nica_custom',
      '#nica_entity' => $nica_entity,
    ];
  }

}