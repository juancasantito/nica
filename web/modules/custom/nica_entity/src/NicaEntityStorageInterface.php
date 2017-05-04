<?php

namespace Drupal\nica_entity;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface NicaEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Nica entity revision IDs for a specific Nica entity.
   *
   * @param \Drupal\nica_entity\Entity\NicaEntityInterface $entity
   *   The Nica entity entity.
   *
   * @return int[]
   *   Nica entity revision IDs (in ascending order).
   */
  public function revisionIds(NicaEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Nica entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Nica entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\nica_entity\Entity\NicaEntityInterface $entity
   *   The Nica entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(NicaEntityInterface $entity);

  /**
   * Unsets the language for all Nica entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
