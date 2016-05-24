<?php

/**
 * @file
 * Contains \Drupal\content_entity_base\Entity\Routing\CrudUiRouteProvider.
 */

namespace Drupal\nica_entity\Entity\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Drupal\content_entity_base\Entity\Routing\CreateHtmlRouteProvider;
use Symfony\Component\Routing\Route;

/**
 * Additional common routes needed for a CRUD UI.
 *
 * - add bundle page
 * - a collection page.
 */
class CrudUiRouteProvider extends CreateHtmlRouteProvider implements EntityRouteProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $routes = parent::getRoutes($entity_type);

    $routes->add('entity.' . $entity_type->id() . '.collection', $this->collectionRoute($entity_type));

    return $routes;
  }

  protected function collectionRoute(EntityTypeInterface $entity_type) {
    $route = new Route($entity_type->getLinkTemplate('collection'));
    $route->setDefault('_title', $entity_type->getLabel() . ' content');
    $route->setDefault('_entity_list', $entity_type->id());
    $route->setRequirement('_permission', 'view ' . $entity_type->id() . ' entity');
    return $route;
  }

  /**
   * {@inheritdoc}
   */
  protected function getAddPageRoute(EntityTypeInterface $entity_type) {
    if ($route = parent::getAddPageRoute($entity_type)) {
      /**
       * @TODO Remove when this feature is at the core.
       */
      $route->setOption('_admin_route', TRUE);
      return $route;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getAddFormRoute(EntityTypeInterface $entity_type) {
    if ($route = parent::getAddFormRoute($entity_type)) {
      $route->setOption('_admin_route', TRUE);
      // See https://github.com/Jaesin/content_entity_base/issues/58.
      $route->setRequirement('_entity_create_access', $entity_type->id() . ':{type}');
      return $route;
    }
  }

}
