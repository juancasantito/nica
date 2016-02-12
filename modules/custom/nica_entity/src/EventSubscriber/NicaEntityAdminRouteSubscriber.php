<?php

/**
 * @file
 * Contains \Drupal\node\EventSubscriber\NodeAdminRouteSubscriber.
 */

namespace Drupal\nica_entity\EventSubscriber;

use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

/**
 * Sets the _admin_route for specific nica_entity routes.
 */
class NicaEntityAdminRouteSubscriber extends RouteSubscriberBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs a new EntityRouteProviderSubscriber instance.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $entity_type = $this->entityManager->getDefinition('nica_entity');
    foreach ($this->entityManager->getRouteProviders('nica_entity') as $route_provider) {
      // Allow to both return an array of routes or a route collection,
      // like route_callbacks in the routing.yml file.

      $routes = $route_provider->getRoutes($entity_type);
      if ($routes instanceof RouteCollection) {
        $routes = $routes->all();
      }
      foreach ($routes as $route_name => $route) {
        // Don't make canonical an admin route.
        if ($route_name != 'entity.nica_entity.canonical') {
          $route->setOption('_admin_route', TRUE);
        }
      }
    }
  }

}
