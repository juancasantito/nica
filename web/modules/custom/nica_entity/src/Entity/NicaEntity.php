<?php

namespace Drupal\nica_entity\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Nica entity entity.
 *
 * @ingroup nica_entity
 *
 * @ContentEntityType(
 *   id = "nica_entity",
 *   label = @Translation("Nica entity"),
 *   bundle_label = @Translation("Nica entity type"),
 *   handlers = {
 *     "storage" = "Drupal\nica_entity\NicaEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\nica_entity\NicaEntityListBuilder",
 *     "views_data" = "Drupal\nica_entity\Entity\NicaEntityViewsData",
 *     "translation" = "Drupal\nica_entity\NicaEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\nica_entity\Form\NicaEntityForm",
 *       "add" = "Drupal\nica_entity\Form\NicaEntityForm",
 *       "edit" = "Drupal\nica_entity\Form\NicaEntityForm",
 *       "delete" = "Drupal\nica_entity\Form\NicaEntityDeleteForm",
 *     },
 *     "access" = "Drupal\nica_entity\NicaEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\nica_entity\NicaEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "nica_entity",
 *   data_table = "nica_entity_field_data",
 *   revision_table = "nica_entity_revision",
 *   revision_data_table = "nica_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer nica entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/nica_entity/{nica_entity}",
 *     "add-page" = "/admin/structure/nica_entity/add",
 *     "add-form" = "/admin/structure/nica_entity/add/{nica_entity_type}",
 *     "edit-form" = "/admin/structure/nica_entity/{nica_entity}/edit",
 *     "delete-form" = "/admin/structure/nica_entity/{nica_entity}/delete",
 *     "version-history" = "/admin/structure/nica_entity/{nica_entity}/revisions",
 *     "revision" = "/admin/structure/nica_entity/{nica_entity}/revisions/{nica_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/nica_entity/{nica_entity}/revisions/{nica_entity_revision}/revert",
 *     "translation_revert" = "/admin/structure/nica_entity/{nica_entity}/revisions/{nica_entity_revision}/revert/{langcode}",
 *     "revision_delete" = "/admin/structure/nica_entity/{nica_entity}/revisions/{nica_entity_revision}/delete",
 *     "collection" = "/admin/structure/nica_entity",
 *   },
 *   bundle_entity_type = "nica_entity_type",
 *   field_ui_base_route = "entity.nica_entity_type.edit_form"
 * )
 */
class NicaEntity extends RevisionableContentEntityBase implements NicaEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the nica_entity owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Nica entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Nica entity entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Nica entity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
