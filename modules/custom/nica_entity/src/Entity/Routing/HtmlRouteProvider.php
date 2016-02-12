<?php

/**
 * @file
 * Contains \Drupal\content_entity_base\Entity\Routing\DefaultHtmlRouteProvider.
 */

namespace Drupal\nica_entity\Entity\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider;

/**
 * @inheritdoc
 *
 * @TODO: Replace with ceb version after https://github.com/Jaesin/content_entity_base/pull/43.
 */
class HtmlRouteProvider extends DefaultHtmlRouteProvider {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);

    $entity_type_id = $entity_type->id();

    if ($edit_route = $collection->get("entity.{$entity_type_id}.edit_form")) {
      $edit_route->setOption('_admin_route', TRUE);
    }
    if ($delete_route = $collection->get("entity.{$entity_type_id}.delete_form")) {
      $delete_route->setOption('_admin_route', TRUE);
    }

    return $collection;
  }

}
