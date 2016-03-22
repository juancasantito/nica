<?php

/**
 * @file
 * Contains \Drupal\content_entity_base\Entity\Access\EntityBaseAccessControlHandler.
 */

namespace Drupal\nica_entity\Entity\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\content_entity_base\Entity\Access\EntityBaseAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for a custom entity type.
 */
class NicaAccessControllerHandler extends EntityBaseAccessControlHandler {
  /**
   * {@inheritdoc}
   *
   * Forking this for https://github.com/Jaesin/content_entity_base/issues/58.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    $access = parent::checkCreateAccess($account, $context, $entity_bundle);
    $entity_type_id = $this->entityTypeId;
    if (!$entity_bundle) {
      $access = $access->orIf(AccessResult::allowedIf($account->hasPermission("access $entity_type_id overview")))->cachePerPermissions();
    }
    else {
      $access = $access->orIf(AccessResult::allowedIf($account->hasPermission("create $entity_bundle $entity_type_id")))->cachePerPermissions();
    }

    return $access;
  }
}
