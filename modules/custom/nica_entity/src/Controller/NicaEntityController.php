<?php

/**
 * @file
 * Contains \Drupal\nica_entity\Controller\NicaEntityController.
 */

namespace Drupal\nica_entity\Controller;

use Drupal\Core\Link;
use Drupal\entity\Controller\EntityCreateController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @inheritdocs
 */
class NicaEntityController extends EntityCreateController {
  /**
   * @inheritdoc
   *
   * Forking this for https://github.com/Jaesin/content_entity_base/issues/58.
   */
  public function addPage($entity_type_id, Request $request) {
    $entity_type = $this->entityTypeManager()->getDefinition($entity_type_id);
    $bundle_type = $entity_type->getBundleEntityType();
    $bundle_key = $entity_type->getKey('bundle');
    $form_route_name = 'entity.' . $entity_type_id . '.add_form';
    $build = [
      '#theme' => 'nica_entity_add_list',
      '#cache' => [
        'tags' => $entity_type->getListCacheTags(),
      ],
      '#bundle_type' => $bundle_type,
    ];
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
    // Filter out the bundles the user doesn't have access to.
    $access_control_handler = $this->entityTypeManager()->getAccessControlHandler($entity_type_id);
    foreach ($bundles as $bundle_name => $bundle_info) {
      $access = $access_control_handler->createAccess($bundle_name, NULL, [], TRUE);
      if (!$access->isAllowed()) {
        unset($bundles[$bundle_name]);
      }
      $this->renderer->addCacheableDependency($build, $access);
    }
    // Redirect if there's only one bundle available.
    if (count($bundles) == 1) {
      $bundle_names = array_keys($bundles);
      $bundle_name = reset($bundle_names);
      return $this->redirect($form_route_name, [$bundle_key => $bundle_name]);
    }
    // Prepare the #bundles array for the template.
    $bundles = $this->loadBundleDescriptions($bundles, $bundle_type);
    foreach ($bundles as $bundle_name => $bundle_info) {
      $build['#bundles'][$bundle_name] = [
        'label' => $bundle_info['label'],
        'description' => $bundle_info['description'],
        'add_link' => Link::createFromRoute($bundle_info['label'], $form_route_name, [$bundle_key => $bundle_name]),
      ];
    }

    return $build;
  }
}
