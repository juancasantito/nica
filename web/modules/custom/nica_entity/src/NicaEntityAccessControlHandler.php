<?php

namespace Drupal\nica_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Nica entity entity.
 *
 * @see \Drupal\nica_entity\Entity\NicaEntity.
 */
class NicaEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\nica_entity\Entity\NicaEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished nica entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published nica entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit nica entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete nica entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add nica entity entities');
  }

}
