<?php

/**
 * @file
 *   Contains \Drupal\nica_entity\Entity\Listing\NicaEntityBaseListBuilder.
 */

namespace Drupal\nica_entity\Entity\Listing;

use Drupal\Core\Entity\EntityInterface;
use Drupal\content_entity_base\Entity\Listing\EntityBaseListBuilder;

/**
 * Defines a class to build a listing of custom entities.
 */
class NicaEntityBaseListBuilder extends EntityBaseListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Nica Label');
    $header['type'] = $this->t('Nica Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->link();
    $row['type'] = $entity->bundle();
    return $row + parent::buildRow($entity);
  }
}
