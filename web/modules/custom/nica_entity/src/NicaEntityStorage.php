<?php

namespace Drupal\nica_entity;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\nica_entity\Entity\NicaEntityInterface;

/**
 * Defines the storage handler class for Nica entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Nica entity entities.
 *
 * @ingroup nica_entity
 */
class NicaEntityStorage extends SqlContentEntityStorage implements NicaEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(NicaEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {nica_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {nica_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(NicaEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {nica_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('nica_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
