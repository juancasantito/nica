<?php

/**
 * @file
 * Contains \Drupal\nica_entity\Entity\Access\NicaEntityContentPermissions.
 */

namespace Drupal\nica_entity\Entity\Access;

use Drupal\Core\Entity\ContentEntityTypeInterface;

/**
 * Defines a class containing permission callbacks.
 */
class NicaEntityContentPermissions extends NicaPermissions {

  /**
   * {@inheritdoc}
   *
   * @todo Leverage https://www.drupal.org/node/2652684
   */
  public function entityPermissions(ContentEntityTypeInterface $entity_type = NULL) {
    return parent::entityPermissions(\Drupal::entityManager()->getDefinition('nica_entity'));
  }

}
